# EODHD Client

A PHP client library for the [EODHD API](https://eodhd.com/) (EOD Historical Data), built the same way as
`shredio/fmp-client`: typed immutable payloads with compiled mappers, streamed parsing of large responses,
fiber-based concurrency, a strict mode and a PSR-16 caching decorator.

## Key Features

- **Memory Efficient**: Large JSON responses (bulk prices, symbol lists, calendars) are streamed using JsonMachine, not loaded entirely into memory
- **Async Support**: Non-blocking concurrent API requests using PHP Fibers via `EodhdPromise`
- **Strong Typing**: All data structures are readonly classes with comprehensive type hints
- **Resilient Fundamentals**: Every section of a fundamentals response and every statement row is mapped on its own, so a drifted value drops only that section or row
- **Validation**: Optional strict mode that turns every schema drift into an exception
- **Caching**: `CacheEodhdClient` caches per-symbol and list endpoints through any PSR-16 cache

## Requirements

- PHP 8.4 or higher
- Symfony HTTP Client
- JsonMachine for streamed JSON parsing

## Installation

```bash
composer require shredio/eodhd-client
```

## Quick Start

```php
use Shredio\EodhdClient\SymfonyEodhdClient;
use Symfony\Component\HttpClient\HttpClient;

$eodhdClient = new SymfonyEodhdClient(HttpClient::create(), 'your-api-token');

$fundamentals = $eodhdClient->fundamentals('CEZ.PR');
if ($fundamentals !== null) {
    echo "{$fundamentals->general->name} ({$fundamentals->general->currencyCode})\n";

    foreach ($fundamentals->financialStatements->annualIncomeStatements as $statement) {
        echo "{$statement->date}: revenue {$statement->totalRevenue}, net income {$statement->netIncome}\n";
    }
}

foreach ($eodhdClient->dividends('CEZ.PR') as $dividend) {
    echo "{$dividend->date}: {$dividend->value} {$dividend->currency}\n";
}
```

Symbols are EODHD tickers including the exchange suffix, e.g. `AAPL.US`, `CEZ.PR`, `TLV.RO` or `SAP.XETRA`.

## Endpoints

| Method | API endpoint |
|---|---|
| `user()` | `user` |
| `exchangesList()` | `exchanges-list/` |
| `exchangeSymbolList($exchange, $delisted)` | `exchange-symbol-list/{EXCHANGE}` |
| `fundamentals($symbol)` | `fundamentals/{SYMBOL}` |
| `endOfDayPrices($symbol, $from, $to, $period)` | `eod/{SYMBOL}` |
| `bulkEndOfDayPrices($exchange, $date, $symbols)` | `eod-bulk-last-day/{EXCHANGE}` |
| `realTimeQuotes($symbols)` | `real-time/{SYMBOL}?s=...` |
| `dividends($symbol, $from, $to)` | `div/{SYMBOL}` |
| `bulkDividends($exchange, $date)` | `eod-bulk-last-day/{EXCHANGE}?type=dividends` |
| `splits($symbol, $from, $to)` | `splits/{SYMBOL}` |
| `bulkSplits($exchange, $date)` | `eod-bulk-last-day/{EXCHANGE}?type=splits` |
| `earningsCalendar($from, $to, $symbols)` | `calendar/earnings` |
| `earningsTrends($symbols)` | `calendar/trends` |
| `request($path, $query)` | any other endpoint, returns the raw `ResponseInterface` |

[OVERVIEW.md](OVERVIEW.md) describes every method and lists every field of every payload.

## Fundamentals

`fundamentals()` returns one `Fundamentals` object per call (10 API requests):

```php
$fundamentals = $eodhdClient->fundamentals('TLV.RO');

$fundamentals->general;             // CompanyGeneral: name, ISIN, sector, fiscal year end, address, officers, ...
$fundamentals->highlights;          // Highlights|null: market cap, EBITDA, P/E, margins, TTM figures
$fundamentals->valuation;           // Valuation|null
$fundamentals->sharesStats;         // SharesStats|null: shares outstanding and float
$fundamentals->technicals;          // Technicals|null: beta, 52-week range, moving averages
$fundamentals->splitsDividends;     // SplitsDividends|null
$fundamentals->earningsHistory;     // list<EarningsHistory>: EPS actual vs. estimate per quarter
$fundamentals->earningsTrends;      // list<EarningsTrend>: analyst estimates per period
$fundamentals->financialStatements; // annual and quarterly income statements, balance sheets and cash flows
```

- `null` is returned for an unknown symbol (HTTP 404) and for an instrument without fundamentals (an empty response).
- Every section is mapped on its own. A section that drifted from the schema becomes `null` and is reported
  through the handler, a list loses only the drifted rows, and the rest of the response is kept.
- A section the client does not know (e.g. `ETF_Data`) is reported as a notice.
- Statement figures are floats as sent by the API. All lists are in the order returned by the API (newest first).

### Data notes

Things that differ from other providers and that a consumer should know about:

- `CashFlowStatement::$capitalExpenditures` and `$dividendsPaid` are positive amounts.
- `FinancialStatements` holds the statement currency per statement type; a single row may also carry its own
  `currencySymbol`, which is null for some rows. The statement currency can differ from the trading currency
  (e.g. EUR statements of a company traded in CZK).
- `CompanyGeneral::$fiscalYearEnd` is not always right: for many Bucharest (`.RO`) listings it says `September`
  or `June` for companies reporting calendar years, and their annual rows are then dated `YYYY-09-30` although
  they hold calendar-year figures. Check the annual rows against the sum of the four quarters before relying on it.
- Splits (`split`) are ratios `new/old` such as `4.000000/1.000000`; bonus shares and stock dividends are
  reported as splits too (e.g. `114.802530/100.000000`).

## Configuration Options

### Strict Mode

Strict mode throws `UnexpectedResponseContentException` on any value that does not match the schema,
including keys the payload does not know:

```php
$strictClient = $eodhdClient->withStrictMode(true);
```

### Unknown Keys

In non-strict mode a payload with unknown keys is returned without them and the problem is passed to the
error handler with `noticesOnly` set to `true`.

### Custom Error Handling

```php
use Shredio\EodhdClient\Exception\UnexpectedResponseContentException;
use Shredio\EodhdClient\Exception\UnexpectedResponseContentExceptionHandler;

$handler = new class implements UnexpectedResponseContentExceptionHandler {
    public function handle(UnexpectedResponseContentException $exception): void {
        if ($exception->noticesOnly) {
            // the payload was returned, e.g. the API added a new key
            error_log(sprintf('EODHD Client Notice: %s (%s)', $exception->getMessage(), $exception->url));
            return;
        }

        // the payload (or fundamentals section, or statement row) was discarded
        error_log(sprintf('EODHD Client Error: %s (%s)', $exception->getMessage(), $exception->url));
    }
};

$eodhdClient = new SymfonyEodhdClient(HttpClient::create(), 'your-api-token', $handler);
```

The URL in the exception never contains the API token.

### HTTP Errors

- Per-symbol endpoints (`fundamentals`, `endOfDayPrices`, `dividends`, `splits`) treat HTTP 404, the API's answer
  for an unknown symbol, as missing data: `null` or an empty result.
- Any other non-200 response throws `UnexpectedHttpCodeException` with the status code, the URL without the
  token and the beginning of the response body (e.g. `Unauthenticated` for a wrong token).

### Retries and Background Processing

Requests are retried through Symfony's `RetryableHttpClient` (3 retries, 500 ms delay by default):

```php
use Shredio\EodhdClient\Config\HttpClientRetryConfiguration;

$eodhdClient = $eodhdClient->withRetryConfiguration(new HttpClientRetryConfiguration(delayMs: 1000, multiplier: 2.0, maxRetries: 5));

// cron jobs and queues: more host connections and a longer back-off
$backgroundClient = $eodhdClient->forBackgroundProcessing();
```

### Concurrent Requests

```php
$promises = [];
foreach (['CEZ.PR', 'KOMB.PR', 'TLV.RO'] as $symbol) {
    $promises[$symbol] = $eodhdClient->promise(fn () => $eodhdClient->fundamentals($symbol));
}

foreach ($promises as $symbol => $promise) {
    $fundamentals = $promise->await();
}
```

### Caching

```php
use Shredio\EodhdClient\CacheEodhdClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

$cachedClient = new CacheEodhdClient($eodhdClient, new Psr16Cache(new FilesystemAdapter()), ttl: 3600);
```

Per-symbol and list endpoints are cached; bulk, real-time, calendar and account endpoints are delegated, because
their results change during the day.

## Development

- `composer test` - PHPUnit tests (fixtures are real API responses)
- `composer phpstan` - static analysis at the maximum level
- `composer compile` - regenerate the mappers in `src/Mapper` after changing a payload
