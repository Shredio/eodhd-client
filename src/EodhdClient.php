<?php declare(strict_types = 1);

namespace Shredio\EodhdClient;

use DateTimeInterface;
use Shredio\EodhdClient\Config\HttpClientRetryConfiguration;
use Shredio\EodhdClient\Enum\PricePeriod;
use Shredio\EodhdClient\Payload\BulkDividend;
use Shredio\EodhdClient\Payload\BulkEndOfDayPrice;
use Shredio\EodhdClient\Payload\BulkSplit;
use Shredio\EodhdClient\Payload\Dividend;
use Shredio\EodhdClient\Payload\EarningsCalendarItem;
use Shredio\EodhdClient\Payload\EarningsTrend;
use Shredio\EodhdClient\Payload\EndOfDayPrice;
use Shredio\EodhdClient\Payload\Exchange;
use Shredio\EodhdClient\Payload\ExchangeSymbol;
use Shredio\EodhdClient\Payload\Fundamentals;
use Shredio\EodhdClient\Payload\RealTimeQuote;
use Shredio\EodhdClient\Payload\Split;
use Shredio\EodhdClient\Payload\User;
use Shredio\EodhdClient\Promise\EodhdPromise;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Symbols are EODHD tickers including the exchange suffix, e.g. `AAPL.US`, `CEZ.PR` or `SAP.XETRA`.
 */
interface EodhdClient
{

	public function withStrictMode(bool $strictMode): self;

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): self;

	/**
	 * Returns a client configured for background tasks (cron jobs, queues) with extended retry settings and more host connections.
	 */
	public function forBackgroundProcessing(): self;

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return EodhdPromise<TReturn>
	 */
	public function promise(callable $fn): EodhdPromise;

	/**
	 * Subscription and today's API usage of the account.
	 *
	 * @see https://eodhd.com/api/user
	 */
	public function user(): ?User;

	/**
	 * @see https://eodhd.com/api/exchanges-list/
	 * @return iterable<int, Exchange>
	 */
	public function exchangesList(): iterable;

	/**
	 * @see https://eodhd.com/api/exchange-symbol-list/{EXCHANGE}
	 * @param non-empty-string $exchange EODHD exchange code, e.g. `US`, `PR`, `XETRA`
	 * @param bool $delisted True lists only the delisted symbols
	 * @return iterable<int, ExchangeSymbol>
	 */
	public function exchangeSymbolList(string $exchange, bool $delisted = false): iterable;

	/**
	 * Company profile, statistics, earnings, estimates and financial statements in one response. One call costs
	 * 10 API requests.
	 *
	 * @see https://eodhd.com/api/fundamentals/{SYMBOL}
	 * @param non-empty-string $symbol
	 * @return Fundamentals|null Null when the symbol is unknown or has no fundamentals (e.g. some ETFs)
	 */
	public function fundamentals(string $symbol): ?Fundamentals;

	/**
	 * @see https://eodhd.com/api/eod/{SYMBOL}
	 * @param non-empty-string $symbol
	 * @return iterable<int, EndOfDayPrice> Oldest first; empty when the symbol is unknown
	 */
	public function endOfDayPrices(
		string $symbol,
		?DateTimeInterface $from = null,
		?DateTimeInterface $to = null,
		PricePeriod $period = PricePeriod::Daily,
	): iterable;

	/**
	 * End-of-day prices of a whole exchange for one trading day.
	 *
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}
	 * @param non-empty-string $exchange
	 * @param DateTimeInterface|null $date Last trading day when null
	 * @param list<non-empty-string> $symbols Limits the response to these symbols
	 * @return iterable<int, BulkEndOfDayPrice>
	 */
	public function bulkEndOfDayPrices(string $exchange, ?DateTimeInterface $date = null, array $symbols = []): iterable;

	/**
	 * Live (delayed) quotes. A symbol unknown to the API is returned with every value null.
	 *
	 * @see https://eodhd.com/api/real-time/{SYMBOL}
	 * @param non-empty-list<non-empty-string> $symbols
	 * @return iterable<int, RealTimeQuote>
	 */
	public function realTimeQuotes(array $symbols): iterable;

	/**
	 * @see https://eodhd.com/api/div/{SYMBOL}
	 * @param non-empty-string $symbol
	 * @return iterable<int, Dividend> Oldest first; empty when the symbol is unknown
	 */
	public function dividends(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable;

	/**
	 * Dividends of a whole exchange going ex on one day.
	 *
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=dividends
	 * @param non-empty-string $exchange
	 * @param DateTimeInterface|null $date Last trading day when null
	 * @return iterable<int, BulkDividend>
	 */
	public function bulkDividends(string $exchange, ?DateTimeInterface $date = null): iterable;

	/**
	 * Splits, including stock dividends and bonus shares expressed as a split ratio.
	 *
	 * @see https://eodhd.com/api/splits/{SYMBOL}
	 * @param non-empty-string $symbol
	 * @return iterable<int, Split> Oldest first; empty when the symbol is unknown
	 */
	public function splits(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable;

	/**
	 * Splits of a whole exchange effective on one day.
	 *
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=splits
	 * @param non-empty-string $exchange
	 * @param DateTimeInterface|null $date Last trading day when null
	 * @return iterable<int, BulkSplit>
	 */
	public function bulkSplits(string $exchange, ?DateTimeInterface $date = null): iterable;

	/**
	 * Historical and upcoming earnings reports.
	 *
	 * @see https://eodhd.com/api/calendar/earnings
	 * @param DateTimeInterface|null $from Today when null
	 * @param DateTimeInterface|null $to Seven days after `$from` when null
	 * @param list<non-empty-string> $symbols Limits the calendar to these symbols; the date range still applies
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(?DateTimeInterface $from = null, ?DateTimeInterface $to = null, array $symbols = []): iterable;

	/**
	 * Analyst estimates (EPS, revenue, revisions) per period for the given symbols.
	 *
	 * @see https://eodhd.com/api/calendar/trends
	 * @param non-empty-list<non-empty-string> $symbols
	 * @return iterable<int, EarningsTrend>
	 */
	public function earningsTrends(array $symbols): iterable;

	/**
	 * Sends a raw request with the API token and `fmt=json` added.
	 *
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface;

}
