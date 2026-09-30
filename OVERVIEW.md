# EODHD Client API Endpoints Overview

This document provides a comprehensive overview of all available endpoints (methods) of the `EodhdClient` interface, their parameters and the complete list of fields of every returned payload.

Symbols are EODHD tickers including the exchange suffix (`AAPL.US`, `CEZ.PR`, `SAP.XETRA`). Dates are `Y-m-d` strings. Every nullable string field maps an empty string from the API to `null`.

## Table of Contents

- [Account](#account)
- [Exchanges and Symbols](#exchanges-and-symbols)
- [Fundamentals](#fundamentals)
- [Prices](#prices)
- [Corporate Actions](#corporate-actions)
- [Calendars](#calendars)
- [Payload Reference](#payload-reference)

---

## Account

### `user()`

```php
public function user(): ?User
```

**Purpose:** Subscription and today's API usage of the account.

**Parameters:** None

**Return Values:** `User|null` - fields in [`User`](#user)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/user`

---

## Exchanges and Symbols

### `exchangesList()`

```php
public function exchangesList(): iterable
```

**Purpose:** All exchanges supported by EODHD.

**Parameters:** None

**Return Values:** `iterable<int, Exchange>` - fields in [`Exchange`](#exchange)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/exchanges-list/`

---

### `exchangeSymbolList()`

```php
public function exchangeSymbolList(string $exchange, bool $delisted = false): iterable
```

**Purpose:** Symbols listed on an exchange (streamed; the US list has about 50 000 rows).

**Parameters:**
- `$exchange` - EODHD exchange code, e.g. `US`, `PR`, `XETRA`
- `$delisted` - true lists only the delisted symbols

**Return Values:** `iterable<int, ExchangeSymbol>` - fields in [`ExchangeSymbol`](#exchangesymbol)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/exchange-symbol-list/{EXCHANGE}`

---

## Fundamentals

### `fundamentals()`

```php
public function fundamentals(string $symbol): ?Fundamentals
```

**Purpose:** Company profile, statistics, earnings, analyst estimates and financial statements in one response. One call costs 10 API requests. Every section is mapped on its own: a section that drifted from the schema is null (a list loses only the drifted rows) and is reported, while the rest is kept.

**Parameters:**
- `$symbol` - e.g. `CEZ.PR`, `AAPL.US`

**Return Values:** `Fundamentals|null` - null when the symbol is unknown (HTTP 404) or has no fundamentals (an empty response, e.g. some ETFs) - fields in [`Fundamentals`](#fundamentals)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/fundamentals/{SYMBOL}`

---

## Prices

### `endOfDayPrices()`

```php
public function endOfDayPrices(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null, PricePeriod $period = PricePeriod::Daily): iterable
```

**Purpose:** Historical end-of-day prices, oldest first. Empty when the symbol is unknown.

**Parameters:**
- `$from`, `$to` - date range (inclusive), the whole history when null
- `$period` - `PricePeriod::Daily`, `Weekly` or `Monthly`

**Return Values:** `iterable<int, EndOfDayPrice>` - fields in [`EndOfDayPrice`](#endofdayprice)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/eod/{SYMBOL}`

---

### `bulkEndOfDayPrices()`

```php
public function bulkEndOfDayPrices(string $exchange, ?DateTimeInterface $date = null, array $symbols = []): iterable
```

**Purpose:** End-of-day prices of a whole exchange for one trading day (streamed).

**Parameters:**
- `$exchange` - EODHD exchange code
- `$date` - trading day, the last one when null
- `$symbols` - limits the response to these symbols

**Return Values:** `iterable<int, BulkEndOfDayPrice>` - fields in [`BulkEndOfDayPrice`](#bulkendofdayprice)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}`

---

### `realTimeQuotes()`

```php
public function realTimeQuotes(array $symbols): iterable
```

**Purpose:** Live (delayed) quotes in one request. A symbol unknown to the API is returned with every value null (the API sends `"NA"`).

**Parameters:**
- `$symbols` - non-empty list of symbols

**Return Values:** `iterable<int, RealTimeQuote>` - fields in [`RealTimeQuote`](#realtimequote)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/real-time/{SYMBOL}?s={OTHER_SYMBOLS}`

---

## Corporate Actions

### `dividends()`

```php
public function dividends(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
```

**Purpose:** Dividend history, oldest first. Empty when the symbol is unknown.

**Parameters:**
- `$from`, `$to` - ex-date range, the whole history when null

**Return Values:** `iterable<int, Dividend>` - fields in [`Dividend`](#dividend)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/div/{SYMBOL}`

---

### `bulkDividends()`

```php
public function bulkDividends(string $exchange, ?DateTimeInterface $date = null): iterable
```

**Purpose:** Dividends of a whole exchange going ex on one day.

**Parameters:**
- `$exchange` - EODHD exchange code
- `$date` - ex-date, the last trading day when null

**Return Values:** `iterable<int, BulkDividend>` - fields in [`BulkDividend`](#bulkdividend)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=dividends`

---

### `splits()`

```php
public function splits(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
```

**Purpose:** Split history, oldest first, including stock dividends and bonus shares expressed as a split ratio. Empty when the symbol is unknown.

**Parameters:**
- `$from`, `$to` - date range, the whole history when null

**Return Values:** `iterable<int, Split>` - fields in [`Split`](#split)

**Cached by `CacheEodhdClient`:** yes

**API endpoint:** `https://eodhd.com/api/splits/{SYMBOL}`

---

### `bulkSplits()`

```php
public function bulkSplits(string $exchange, ?DateTimeInterface $date = null): iterable
```

**Purpose:** Splits of a whole exchange effective on one day.

**Parameters:**
- `$exchange` - EODHD exchange code
- `$date` - split date, the last trading day when null

**Return Values:** `iterable<int, BulkSplit>` - fields in [`BulkSplit`](#bulksplit)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=splits`

---

## Calendars

### `earningsCalendar()`

```php
public function earningsCalendar(?DateTimeInterface $from = null, ?DateTimeInterface $to = null, array $symbols = []): iterable
```

**Purpose:** Historical and upcoming earnings reports (streamed).

**Parameters:**
- `$from` - today when null
- `$to` - seven days after `$from` when null
- `$symbols` - limits the calendar to these symbols; the date range still applies

**Return Values:** `iterable<int, EarningsCalendarItem>` - fields in [`EarningsCalendarItem`](#earningscalendaritem)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/calendar/earnings`

---

### `earningsTrends()`

```php
public function earningsTrends(array $symbols): iterable
```

**Purpose:** Analyst estimates (EPS, revenue, revisions) per period; the per-symbol lists of the response are flattened.

**Parameters:**
- `$symbols` - non-empty list of symbols

**Return Values:** `iterable<int, EarningsTrend>` - fields in [`EarningsTrend`](#earningstrend)

**Cached by `CacheEodhdClient`:** no (delegated)

**API endpoint:** `https://eodhd.com/api/calendar/trends`

---

## Payload Reference

Every payload is a `final readonly` class with public properties and a `toArray()` method returning the same fields. The table lists each property, its type and the key it is read from in the API response.

### `Fundamentals`

Container of the mapped sections of a fundamentals response.

| Property | Type | API key | Note |
|---|---|---|---|
| `symbol` | `non-empty-string` | - | Symbol the fundamentals were requested for |
| `general` | `CompanyGeneral` | `General` | Always present |
| `highlights` | `Highlights|null` | `Highlights` |  |
| `valuation` | `Valuation|null` | `Valuation` |  |
| `sharesStats` | `SharesStats|null` | `SharesStats` |  |
| `technicals` | `Technicals|null` | `Technicals` |  |
| `splitsDividends` | `SplitsDividends|null` | `SplitsDividends` |  |
| `analystRatings` | `AnalystRatings|null` | `AnalystRatings` | Mostly US listings |
| `esgScores` | `EsgScores|null` | `ESGScores` | Mostly US listings |
| `holders` | `Holders|null` | `Holders` | Mostly US listings |
| `insiderTransactions` | `list<InsiderTransaction>` | `InsiderTransactions` |  |
| `annualOutstandingShares` | `list<OutstandingShares>` | `outstandingShares.annual` | Newest first |
| `quarterlyOutstandingShares` | `list<OutstandingShares>` | `outstandingShares.quarterly` | Newest first |
| `earningsHistory` | `list<EarningsHistory>` | `Earnings.History` | Reported and upcoming quarters, newest first |
| `earningsTrends` | `list<EarningsTrend>` | `Earnings.Trend` | Estimates per period (`0q`, `+1q`, `0y`, `+1y`, ...) |
| `annualEarnings` | `list<AnnualEarnings>` | `Earnings.Annual` | Newest first |
| `financialStatements` | `FinancialStatements` | `Financials` |  |

### `FinancialStatements`

Each list is in the order returned by the API (newest first). A single statement row may carry its own `currencySymbol`, which is null for some rows.

| Property | Type | API key |
|---|---|---|
| `incomeStatementCurrency` | `non-empty-string|null` | `Financials.Income_Statement.currency_symbol` |
| `balanceSheetCurrency` | `non-empty-string|null` | `Financials.Balance_Sheet.currency_symbol` |
| `cashFlowCurrency` | `non-empty-string|null` | `Financials.Cash_Flow.currency_symbol` |
| `annualIncomeStatements` | `list<IncomeStatement>` | `Financials.Income_Statement.yearly` |
| `quarterlyIncomeStatements` | `list<IncomeStatement>` | `Financials.Income_Statement.quarterly` |
| `annualBalanceSheetStatements` | `list<BalanceSheetStatement>` | `Financials.Balance_Sheet.yearly` |
| `quarterlyBalanceSheetStatements` | `list<BalanceSheetStatement>` | `Financials.Balance_Sheet.quarterly` |
| `annualCashFlowStatements` | `list<CashFlowStatement>` | `Financials.Cash_Flow.yearly` |
| `quarterlyCashFlowStatements` | `list<CashFlowStatement>` | `Financials.Cash_Flow.quarterly` |

### Account and exchanges

#### `User`

| Property | Type | API key |
|---|---|---|
| `name` | `non-empty-string\|null` | `name` |
| `email` | `non-empty-string\|null` | `email` |
| `subscriptionType` | `non-empty-string\|null` | `subscriptionType` |
| `paymentMethod` | `non-empty-string\|null` | `paymentMethod` |
| `apiRequests` | `int` | `apiRequests` |
| `apiRequestsDate` | `non-empty-string` | `apiRequestsDate` |
| `dailyRateLimit` | `int` | `dailyRateLimit` |
| `extraLimit` | `int` | `extraLimit` |
| `inviteToken` | `non-empty-string\|null` | `inviteToken` |
| `inviteTokenClicked` | `int` | `inviteTokenClicked` |
| `subscriptionMode` | `non-empty-string\|null` | `subscriptionMode` |
| `canManageOrganizations` | `bool` | `canManageOrganizations` |

#### `Exchange`

| Property | Type | API key |
|---|---|---|
| `name` | `non-empty-string` | `Name` |
| `code` | `non-empty-string` | `Code` |
| `operatingMic` | `non-empty-string\|null` | `OperatingMIC` |
| `country` | `non-empty-string\|null` | `Country` |
| `currency` | `non-empty-string\|null` | `Currency` |
| `countryIso2` | `non-empty-string\|null` | `CountryISO2` |
| `countryIso3` | `non-empty-string\|null` | `CountryISO3` |

#### `ExchangeSymbol`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `Code` |
| `name` | `string` | `Name` |
| `country` | `non-empty-string\|null` | `Country` |
| `exchange` | `non-empty-string` | `Exchange` |
| `currency` | `non-empty-string\|null` | `Currency` |
| `type` | `non-empty-string\|null` | `Type` |
| `isin` | `non-empty-string\|null` | `Isin` |

### Prices

#### `EndOfDayPrice`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `open` | `float` | `open` |
| `high` | `float` | `high` |
| `low` | `float` | `low` |
| `close` | `float` | `close` |
| `adjustedClose` | `float` | `adjusted_close` |
| `volume` | `int\|float` | `volume` |

#### `BulkEndOfDayPrice`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `code` |
| `exchangeShortName` | `non-empty-string` | `exchange_short_name` |
| `date` | `non-empty-string` | `date` |
| `open` | `float` | `open` |
| `high` | `float` | `high` |
| `low` | `float` | `low` |
| `close` | `float` | `close` |
| `adjustedClose` | `float` | `adjusted_close` |
| `volume` | `int\|float` | `volume` |

#### `RealTimeQuote`

`timestamp` is a Unix timestamp; `gmtOffset` is in seconds.

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `code` |
| `timestamp` | `int\|null` | `timestamp` |
| `gmtOffset` | `int` | `gmtoffset` |
| `open` | `float\|null` | `open` |
| `high` | `float\|null` | `high` |
| `low` | `float\|null` | `low` |
| `close` | `float\|null` | `close` |
| `volume` | `int\|null` | `volume` |
| `previousClose` | `float\|null` | `previousClose` |
| `change` | `float\|null` | `change` |
| `changePercent` | `float\|null` | `change_p` |

### Corporate actions

#### `Dividend`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `declarationDate` | `non-empty-string\|null` | `declarationDate` |
| `recordDate` | `non-empty-string\|null` | `recordDate` |
| `paymentDate` | `non-empty-string\|null` | `paymentDate` |
| `period` | `non-empty-string\|null` | `period` |
| `value` | `float` | `value` |
| `unadjustedValue` | `float` | `unadjustedValue` |
| `currency` | `non-empty-string\|null` | `currency` |

#### `BulkDividend`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `code` |
| `exchange` | `non-empty-string` | `exchange` |
| `date` | `non-empty-string` | `date` |
| `dividend` | `float` | `dividend` |
| `currency` | `non-empty-string\|null` | `currency` |
| `declarationDate` | `non-empty-string\|null` | `declarationDate` |
| `recordDate` | `non-empty-string\|null` | `recordDate` |
| `paymentDate` | `non-empty-string\|null` | `paymentDate` |
| `period` | `non-empty-string\|null` | `period` |
| `unadjustedValue` | `float` | `unadjustedValue` |

#### `Split`

`split` is the ratio `new/old` as sent by the API, e.g. `4.000000/1.000000` for a 4:1 split or `114.802530/100.000000` for bonus shares.

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `split` | `non-empty-string` | `split` |

#### `BulkSplit`

See `Split` for the ratio format.

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `code` |
| `exchange` | `non-empty-string` | `exchange` |
| `date` | `non-empty-string` | `date` |
| `split` | `non-empty-string` | `split` |

### Calendars and estimates

#### `EarningsCalendarItem`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `code` |
| `reportDate` | `non-empty-string` | `report_date` |
| `date` | `non-empty-string` | `date` |
| `beforeAfterMarket` | `non-empty-string\|null` | `before_after_market` |
| `currency` | `non-empty-string\|null` | `currency` |
| `actual` | `float\|null` | `actual` |
| `estimate` | `float\|null` | `estimate` |
| `difference` | `float\|null` | `difference` |
| `percent` | `float\|null` | `percent` |

#### `EarningsTrend`

Returned by `earningsTrends()` and inside `Fundamentals::$earningsTrends`. `code` is set by the calendar only, `epsRevisionsDownLast7days` by fundamentals only.

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `period` | `non-empty-string` | `period` |
| `growth` | `float\|null` | `growth` |
| `earningsEstimateAvg` | `float\|null` | `earningsEstimateAvg` |
| `earningsEstimateLow` | `float\|null` | `earningsEstimateLow` |
| `earningsEstimateHigh` | `float\|null` | `earningsEstimateHigh` |
| `earningsEstimateYearAgoEps` | `float\|null` | `earningsEstimateYearAgoEps` |
| `earningsEstimateNumberOfAnalysts` | `float\|null` | `earningsEstimateNumberOfAnalysts` |
| `earningsEstimateGrowth` | `float\|null` | `earningsEstimateGrowth` |
| `revenueEstimateAvg` | `float\|null` | `revenueEstimateAvg` |
| `revenueEstimateLow` | `float\|null` | `revenueEstimateLow` |
| `revenueEstimateHigh` | `float\|null` | `revenueEstimateHigh` |
| `revenueEstimateYearAgoEps` | `float\|null` | `revenueEstimateYearAgoEps` |
| `revenueEstimateNumberOfAnalysts` | `float\|null` | `revenueEstimateNumberOfAnalysts` |
| `revenueEstimateGrowth` | `float\|null` | `revenueEstimateGrowth` |
| `epsTrendCurrent` | `float\|null` | `epsTrendCurrent` |
| `epsTrend7daysAgo` | `float\|null` | `epsTrend7daysAgo` |
| `epsTrend30daysAgo` | `float\|null` | `epsTrend30daysAgo` |
| `epsTrend60daysAgo` | `float\|null` | `epsTrend60daysAgo` |
| `epsTrend90daysAgo` | `float\|null` | `epsTrend90daysAgo` |
| `epsRevisionsUpLast7days` | `float\|null` | `epsRevisionsUpLast7days` |
| `epsRevisionsUpLast30days` | `float\|null` | `epsRevisionsUpLast30days` |
| `epsRevisionsDownLast30days` | `float\|null` | `epsRevisionsDownLast30days` |
| `epsRevisionsDownLast7days` | `float\|null` | `epsRevisionsDownLast7days` (optional) |
| `code` | `non-empty-string\|null` | `code` (optional) |

### Fundamentals sections

#### `CompanyGeneral`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `Code` |
| `type` | `non-empty-string\|null` | `Type` |
| `name` | `non-empty-string` | `Name` |
| `exchange` | `non-empty-string` | `Exchange` |
| `currencyCode` | `non-empty-string\|null` | `CurrencyCode` |
| `currencyName` | `non-empty-string\|null` | `CurrencyName` |
| `currencySymbol` | `non-empty-string\|null` | `CurrencySymbol` |
| `countryName` | `non-empty-string\|null` | `CountryName` |
| `countryIso` | `non-empty-string\|null` | `CountryISO` |
| `openFigi` | `non-empty-string\|null` | `OpenFigi` |
| `isin` | `non-empty-string\|null` | `ISIN` |
| `lei` | `non-empty-string\|null` | `LEI` |
| `primaryTicker` | `non-empty-string\|null` | `PrimaryTicker` |
| `cik` | `non-empty-string\|null` | `CIK` |
| `employerIdNumber` | `non-empty-string\|null` | `EmployerIdNumber` |
| `fiscalYearEnd` | `non-empty-string\|null` | `FiscalYearEnd` |
| `ipoDate` | `non-empty-string\|null` | `IPODate` |
| `internationalDomestic` | `non-empty-string\|null` | `InternationalDomestic` |
| `sector` | `non-empty-string\|null` | `Sector` |
| `industry` | `non-empty-string\|null` | `Industry` |
| `gicSector` | `non-empty-string\|null` | `GicSector` |
| `gicGroup` | `non-empty-string\|null` | `GicGroup` |
| `gicIndustry` | `non-empty-string\|null` | `GicIndustry` |
| `gicSubIndustry` | `non-empty-string\|null` | `GicSubIndustry` |
| `description` | `non-empty-string\|null` | `Description` |
| `address` | `non-empty-string\|null` | `Address` |
| `addressData` | `CompanyAddress\|null` | `AddressData` |
| `listings` | `list<CompanyListing>` | `Listings` |
| `officers` | `list<CompanyOfficer>` | `Officers` |
| `phone` | `non-empty-string\|null` | `Phone` |
| `webUrl` | `non-empty-string\|null` | `WebURL` |
| `logoUrl` | `non-empty-string\|null` | `LogoURL` |
| `fullTimeEmployees` | `int\|null` | `FullTimeEmployees` |
| `updatedAt` | `non-empty-string\|null` | `UpdatedAt` |
| `cusip` | `non-empty-string\|null` | `CUSIP` (optional) |
| `homeCategory` | `non-empty-string\|null` | `HomeCategory` (optional) |
| `isDelisted` | `bool\|null` | `IsDelisted` (optional) |

#### `CompanyAddress`

| Property | Type | API key |
|---|---|---|
| `country` | `non-empty-string\|null` | `Country` |
| `street` | `non-empty-string\|null` | `Street` (optional) |
| `city` | `non-empty-string\|null` | `City` (optional) |
| `state` | `non-empty-string\|null` | `State` (optional) |
| `zip` | `non-empty-string\|null` | `ZIP` (optional) |

#### `CompanyListing`

| Property | Type | API key |
|---|---|---|
| `code` | `non-empty-string` | `Code` |
| `exchange` | `non-empty-string` | `Exchange` |
| `name` | `string` | `Name` |

#### `CompanyOfficer`

`yearBorn` is null when the API sends `"NA"`.

| Property | Type | API key |
|---|---|---|
| `name` | `string` | `Name` |
| `title` | `non-empty-string\|null` | `Title` |
| `yearBorn` | `int\|null` | `YearBorn` |

#### `Highlights`

| Property | Type | API key |
|---|---|---|
| `marketCapitalization` | `int\|null` | `MarketCapitalization` |
| `marketCapitalizationMln` | `float\|null` | `MarketCapitalizationMln` |
| `ebitda` | `int\|null` | `EBITDA` |
| `peRatio` | `float\|null` | `PERatio` |
| `pegRatio` | `float\|null` | `PEGRatio` |
| `wallStreetTargetPrice` | `float\|null` | `WallStreetTargetPrice` |
| `bookValue` | `float\|null` | `BookValue` |
| `dividendShare` | `float\|null` | `DividendShare` |
| `dividendYield` | `float\|null` | `DividendYield` |
| `earningsShare` | `float\|null` | `EarningsShare` |
| `epsEstimateCurrentYear` | `float\|null` | `EPSEstimateCurrentYear` |
| `epsEstimateNextYear` | `float\|null` | `EPSEstimateNextYear` |
| `epsEstimateNextQuarter` | `float\|null` | `EPSEstimateNextQuarter` |
| `epsEstimateCurrentQuarter` | `float\|null` | `EPSEstimateCurrentQuarter` |
| `mostRecentQuarter` | `non-empty-string\|null` | `MostRecentQuarter` |
| `profitMargin` | `float\|null` | `ProfitMargin` |
| `operatingMarginTtm` | `float\|null` | `OperatingMarginTTM` |
| `returnOnAssetsTtm` | `float\|null` | `ReturnOnAssetsTTM` |
| `returnOnEquityTtm` | `float\|null` | `ReturnOnEquityTTM` |
| `revenueTtm` | `int\|null` | `RevenueTTM` |
| `revenuePerShareTtm` | `float\|null` | `RevenuePerShareTTM` |
| `quarterlyRevenueGrowthYoy` | `float\|null` | `QuarterlyRevenueGrowthYOY` |
| `grossProfitTtm` | `int\|null` | `GrossProfitTTM` |
| `dilutedEpsTtm` | `float\|null` | `DilutedEpsTTM` |
| `quarterlyEarningsGrowthYoy` | `float\|null` | `QuarterlyEarningsGrowthYOY` |

#### `Valuation`

| Property | Type | API key |
|---|---|---|
| `trailingPe` | `float\|null` | `TrailingPE` |
| `forwardPe` | `float\|null` | `ForwardPE` |
| `priceSalesTtm` | `float\|null` | `PriceSalesTTM` |
| `priceBookMrq` | `float\|null` | `PriceBookMRQ` |
| `enterpriseValue` | `int\|null` | `EnterpriseValue` |
| `enterpriseValueRevenue` | `float\|null` | `EnterpriseValueRevenue` |
| `enterpriseValueEbitda` | `float\|null` | `EnterpriseValueEbitda` |

#### `SharesStats`

| Property | Type | API key |
|---|---|---|
| `sharesOutstanding` | `int\|null` | `SharesOutstanding` |
| `sharesFloat` | `int\|null` | `SharesFloat` |
| `percentInsiders` | `float\|null` | `PercentInsiders` |
| `percentInstitutions` | `float\|null` | `PercentInstitutions` |
| `sharesShort` | `int\|null` | `SharesShort` |
| `sharesShortPriorMonth` | `int\|null` | `SharesShortPriorMonth` |
| `shortRatio` | `float\|null` | `ShortRatio` |
| `shortPercentOutstanding` | `float\|null` | `ShortPercentOutstanding` |
| `shortPercentFloat` | `float\|null` | `ShortPercentFloat` |

#### `Technicals`

| Property | Type | API key |
|---|---|---|
| `beta` | `float\|null` | `Beta` |
| `fiftyTwoWeekHigh` | `float\|null` | `52WeekHigh` |
| `fiftyTwoWeekLow` | `float\|null` | `52WeekLow` |
| `fiftyDayMovingAverage` | `float\|null` | `50DayMA` |
| `twoHundredDayMovingAverage` | `float\|null` | `200DayMA` |
| `sharesShort` | `int\|null` | `SharesShort` |
| `sharesShortPriorMonth` | `int\|null` | `SharesShortPriorMonth` |
| `shortRatio` | `float\|null` | `ShortRatio` |
| `shortPercent` | `float\|null` | `ShortPercent` |

#### `SplitsDividends`

| Property | Type | API key |
|---|---|---|
| `forwardAnnualDividendRate` | `float\|null` | `ForwardAnnualDividendRate` |
| `forwardAnnualDividendYield` | `float\|null` | `ForwardAnnualDividendYield` |
| `payoutRatio` | `float\|null` | `PayoutRatio` |
| `dividendDate` | `non-empty-string\|null` | `DividendDate` |
| `exDividendDate` | `non-empty-string\|null` | `ExDividendDate` |
| `lastSplitFactor` | `non-empty-string\|null` | `LastSplitFactor` |
| `lastSplitDate` | `non-empty-string\|null` | `LastSplitDate` |
| `numberDividendsByYear` | `list<DividendCountByYear>` | `NumberDividendsByYear` |

#### `DividendCountByYear`

| Property | Type | API key |
|---|---|---|
| `year` | `int` | `Year` |
| `count` | `int` | `Count` |

#### `AnalystRatings`

| Property | Type | API key |
|---|---|---|
| `rating` | `float\|null` | `Rating` |
| `targetPrice` | `float\|null` | `TargetPrice` |
| `strongBuy` | `int\|null` | `StrongBuy` |
| `buy` | `int\|null` | `Buy` |
| `hold` | `int\|null` | `Hold` |
| `sell` | `int\|null` | `Sell` |
| `strongSell` | `int\|null` | `StrongSell` |

#### `EsgScores`

| Property | Type | API key |
|---|---|---|
| `disclaimer` | `non-empty-string\|null` | `Disclaimer` |
| `ratingDate` | `non-empty-string\|null` | `RatingDate` |
| `totalEsg` | `float\|null` | `TotalEsg` |
| `totalEsgPercentile` | `float\|null` | `TotalEsgPercentile` |
| `environmentScore` | `float\|null` | `EnvironmentScore` |
| `environmentScorePercentile` | `float\|null` | `EnvironmentScorePercentile` |
| `socialScore` | `float\|null` | `SocialScore` |
| `socialScorePercentile` | `float\|null` | `SocialScorePercentile` |
| `governanceScore` | `float\|null` | `GovernanceScore` |
| `governanceScorePercentile` | `float\|null` | `GovernanceScorePercentile` |
| `controversyLevel` | `int\|null` | `ControversyLevel` |
| `activitiesInvolvement` | `list<EsgActivityInvolvement>` | `ActivitiesInvolvement` |

#### `EsgActivityInvolvement`

| Property | Type | API key |
|---|---|---|
| `activity` | `non-empty-string` | `Activity` |
| `involvement` | `non-empty-string\|null` | `Involvement` |

#### `Holders`

| Property | Type | API key |
|---|---|---|
| `institutions` | `list<Holder>` | `Institutions` |
| `funds` | `list<Holder>` | `Funds` |

#### `Holder`

`totalShares` and `totalAssets` are percentages.

| Property | Type | API key |
|---|---|---|
| `name` | `string` | `name` |
| `date` | `non-empty-string\|null` | `date` |
| `totalShares` | `float\|null` | `totalShares` |
| `totalAssets` | `float\|null` | `totalAssets` |
| `currentShares` | `int\|null` | `currentShares` |
| `change` | `int\|null` | `change` |
| `changePercent` | `float\|null` | `change_p` |

#### `InsiderTransaction`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string\|null` | `date` |
| `ownerCik` | `non-empty-string\|null` | `ownerCik` |
| `ownerName` | `non-empty-string\|null` | `ownerName` |
| `transactionDate` | `non-empty-string\|null` | `transactionDate` |
| `transactionCode` | `non-empty-string\|null` | `transactionCode` |
| `transactionAmount` | `int\|null` | `transactionAmount` |
| `transactionPrice` | `float\|null` | `transactionPrice` |
| `transactionAcquiredDisposed` | `non-empty-string\|null` | `transactionAcquiredDisposed` |
| `postTransactionAmount` | `int\|null` | `postTransactionAmount` |
| `secLink` | `non-empty-string\|null` | `secLink` |

#### `OutstandingShares`

`date` is the period label, e.g. `2026` or `2026-Q2`; `dateFormatted` is the period end date.

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `dateFormatted` | `non-empty-string` | `dateFormatted` |
| `sharesMln` | `float\|null` | `sharesMln` |
| `shares` | `int\|null` | `shares` |

#### `EarningsHistory`

| Property | Type | API key |
|---|---|---|
| `reportDate` | `non-empty-string\|null` | `reportDate` |
| `date` | `non-empty-string` | `date` |
| `beforeAfterMarket` | `non-empty-string\|null` | `beforeAfterMarket` |
| `currency` | `non-empty-string\|null` | `currency` |
| `epsActual` | `float\|null` | `epsActual` |
| `epsEstimate` | `float\|null` | `epsEstimate` |
| `epsDifference` | `float\|null` | `epsDifference` |
| `surprisePercent` | `float\|null` | `surprisePercent` |

#### `AnnualEarnings`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `epsActual` | `float\|null` | `epsActual` |

### Financial statements

#### `IncomeStatement`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `filingDate` | `non-empty-string\|null` | `filing_date` |
| `currencySymbol` | `non-empty-string\|null` | `currency_symbol` |
| `researchDevelopment` | `float\|null` | `researchDevelopment` |
| `effectOfAccountingCharges` | `float\|null` | `effectOfAccountingCharges` |
| `incomeBeforeTax` | `float\|null` | `incomeBeforeTax` |
| `minorityInterest` | `float\|null` | `minorityInterest` |
| `netIncome` | `float\|null` | `netIncome` |
| `sellingGeneralAdministrative` | `float\|null` | `sellingGeneralAdministrative` |
| `sellingAndMarketingExpenses` | `float\|null` | `sellingAndMarketingExpenses` |
| `grossProfit` | `float\|null` | `grossProfit` |
| `reconciledDepreciation` | `float\|null` | `reconciledDepreciation` |
| `ebit` | `float\|null` | `ebit` |
| `ebitda` | `float\|null` | `ebitda` |
| `depreciationAndAmortization` | `float\|null` | `depreciationAndAmortization` |
| `nonOperatingIncomeNetOther` | `float\|null` | `nonOperatingIncomeNetOther` |
| `operatingIncome` | `float\|null` | `operatingIncome` |
| `otherOperatingExpenses` | `float\|null` | `otherOperatingExpenses` |
| `interestExpense` | `float\|null` | `interestExpense` |
| `taxProvision` | `float\|null` | `taxProvision` |
| `interestIncome` | `float\|null` | `interestIncome` |
| `netInterestIncome` | `float\|null` | `netInterestIncome` |
| `extraordinaryItems` | `float\|null` | `extraordinaryItems` |
| `nonRecurring` | `float\|null` | `nonRecurring` |
| `otherItems` | `float\|null` | `otherItems` |
| `incomeTaxExpense` | `float\|null` | `incomeTaxExpense` |
| `totalRevenue` | `float\|null` | `totalRevenue` |
| `totalOperatingExpenses` | `float\|null` | `totalOperatingExpenses` |
| `costOfRevenue` | `float\|null` | `costOfRevenue` |
| `totalOtherIncomeExpenseNet` | `float\|null` | `totalOtherIncomeExpenseNet` |
| `discontinuedOperations` | `float\|null` | `discontinuedOperations` |
| `netIncomeFromContinuingOps` | `float\|null` | `netIncomeFromContinuingOps` |
| `netIncomeApplicableToCommonShares` | `float\|null` | `netIncomeApplicableToCommonShares` |
| `preferredStockAndOtherAdjustments` | `float\|null` | `preferredStockAndOtherAdjustments` |

#### `BalanceSheetStatement`

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `filingDate` | `non-empty-string\|null` | `filing_date` |
| `currencySymbol` | `non-empty-string\|null` | `currency_symbol` |
| `totalAssets` | `float\|null` | `totalAssets` |
| `intangibleAssets` | `float\|null` | `intangibleAssets` |
| `earningAssets` | `float\|null` | `earningAssets` |
| `otherCurrentAssets` | `float\|null` | `otherCurrentAssets` |
| `totalLiab` | `float\|null` | `totalLiab` |
| `totalStockholderEquity` | `float\|null` | `totalStockholderEquity` |
| `deferredLongTermLiab` | `float\|null` | `deferredLongTermLiab` |
| `otherCurrentLiab` | `float\|null` | `otherCurrentLiab` |
| `commonStock` | `float\|null` | `commonStock` |
| `capitalStock` | `float\|null` | `capitalStock` |
| `retainedEarnings` | `float\|null` | `retainedEarnings` |
| `otherLiab` | `float\|null` | `otherLiab` |
| `goodWill` | `float\|null` | `goodWill` |
| `otherAssets` | `float\|null` | `otherAssets` |
| `cash` | `float\|null` | `cash` |
| `cashAndEquivalents` | `float\|null` | `cashAndEquivalents` |
| `totalCurrentLiabilities` | `float\|null` | `totalCurrentLiabilities` |
| `currentDeferredRevenue` | `float\|null` | `currentDeferredRevenue` |
| `netDebt` | `float\|null` | `netDebt` |
| `shortTermDebt` | `float\|null` | `shortTermDebt` |
| `shortLongTermDebt` | `float\|null` | `shortLongTermDebt` |
| `shortLongTermDebtTotal` | `float\|null` | `shortLongTermDebtTotal` |
| `otherStockholderEquity` | `float\|null` | `otherStockholderEquity` |
| `propertyPlantEquipment` | `float\|null` | `propertyPlantEquipment` |
| `totalCurrentAssets` | `float\|null` | `totalCurrentAssets` |
| `longTermInvestments` | `float\|null` | `longTermInvestments` |
| `netTangibleAssets` | `float\|null` | `netTangibleAssets` |
| `shortTermInvestments` | `float\|null` | `shortTermInvestments` |
| `netReceivables` | `float\|null` | `netReceivables` |
| `longTermDebt` | `float\|null` | `longTermDebt` |
| `inventory` | `float\|null` | `inventory` |
| `accountsPayable` | `float\|null` | `accountsPayable` |
| `totalPermanentEquity` | `float\|null` | `totalPermanentEquity` |
| `noncontrollingInterestInConsolidatedEntity` | `float\|null` | `noncontrollingInterestInConsolidatedEntity` |
| `temporaryEquityRedeemableNoncontrollingInterests` | `float\|null` | `temporaryEquityRedeemableNoncontrollingInterests` |
| `accumulatedOtherComprehensiveIncome` | `float\|null` | `accumulatedOtherComprehensiveIncome` |
| `additionalPaidInCapital` | `float\|null` | `additionalPaidInCapital` |
| `commonStockTotalEquity` | `float\|null` | `commonStockTotalEquity` |
| `preferredStockTotalEquity` | `float\|null` | `preferredStockTotalEquity` |
| `retainedEarningsTotalEquity` | `float\|null` | `retainedEarningsTotalEquity` |
| `treasuryStock` | `float\|null` | `treasuryStock` |
| `accumulatedAmortization` | `float\|null` | `accumulatedAmortization` |
| `nonCurrentAssetsOther` | `float\|null` | `nonCurrrentAssetsOther` |
| `deferredLongTermAssetCharges` | `float\|null` | `deferredLongTermAssetCharges` |
| `nonCurrentAssetsTotal` | `float\|null` | `nonCurrentAssetsTotal` |
| `capitalLeaseObligations` | `float\|null` | `capitalLeaseObligations` |
| `longTermDebtTotal` | `float\|null` | `longTermDebtTotal` |
| `nonCurrentLiabilitiesOther` | `float\|null` | `nonCurrentLiabilitiesOther` |
| `nonCurrentLiabilitiesTotal` | `float\|null` | `nonCurrentLiabilitiesTotal` |
| `negativeGoodwill` | `float\|null` | `negativeGoodwill` |
| `warrants` | `float\|null` | `warrants` |
| `preferredStockRedeemable` | `float\|null` | `preferredStockRedeemable` |
| `capitalSurplus` | `float\|null` | `capitalSurpluse` |
| `liabilitiesAndStockholdersEquity` | `float\|null` | `liabilitiesAndStockholdersEquity` |
| `cashAndShortTermInvestments` | `float\|null` | `cashAndShortTermInvestments` |
| `propertyPlantAndEquipmentGross` | `float\|null` | `propertyPlantAndEquipmentGross` |
| `propertyPlantAndEquipmentNet` | `float\|null` | `propertyPlantAndEquipmentNet` |
| `accumulatedDepreciation` | `float\|null` | `accumulatedDepreciation` |
| `netWorkingCapital` | `float\|null` | `netWorkingCapital` |
| `netInvestedCapital` | `float\|null` | `netInvestedCapital` |
| `commonStockSharesOutstanding` | `float\|null` | `commonStockSharesOutstanding` |

#### `CashFlowStatement`

`capitalExpenditures` and `dividendsPaid` are positive amounts (outflows), unlike the negative outflows of most other providers.

| Property | Type | API key |
|---|---|---|
| `date` | `non-empty-string` | `date` |
| `filingDate` | `non-empty-string\|null` | `filing_date` |
| `currencySymbol` | `non-empty-string\|null` | `currency_symbol` |
| `investments` | `float\|null` | `investments` |
| `changeToLiabilities` | `float\|null` | `changeToLiabilities` |
| `totalCashflowsFromInvestingActivities` | `float\|null` | `totalCashflowsFromInvestingActivities` |
| `netBorrowings` | `float\|null` | `netBorrowings` |
| `totalCashFromFinancingActivities` | `float\|null` | `totalCashFromFinancingActivities` |
| `changeToOperatingActivities` | `float\|null` | `changeToOperatingActivities` |
| `netIncome` | `float\|null` | `netIncome` |
| `changeInCash` | `float\|null` | `changeInCash` |
| `beginPeriodCashFlow` | `float\|null` | `beginPeriodCashFlow` |
| `endPeriodCashFlow` | `float\|null` | `endPeriodCashFlow` |
| `totalCashFromOperatingActivities` | `float\|null` | `totalCashFromOperatingActivities` |
| `issuanceOfCapitalStock` | `float\|null` | `issuanceOfCapitalStock` |
| `depreciation` | `float\|null` | `depreciation` |
| `otherCashflowsFromInvestingActivities` | `float\|null` | `otherCashflowsFromInvestingActivities` |
| `dividendsPaid` | `float\|null` | `dividendsPaid` |
| `changeToInventory` | `float\|null` | `changeToInventory` |
| `changeToAccountReceivables` | `float\|null` | `changeToAccountReceivables` |
| `salePurchaseOfStock` | `float\|null` | `salePurchaseOfStock` |
| `otherCashflowsFromFinancingActivities` | `float\|null` | `otherCashflowsFromFinancingActivities` |
| `changeToNetIncome` | `float\|null` | `changeToNetincome` |
| `capitalExpenditures` | `float\|null` | `capitalExpenditures` |
| `changeReceivables` | `float\|null` | `changeReceivables` |
| `cashFlowsOtherOperating` | `float\|null` | `cashFlowsOtherOperating` |
| `exchangeRateChanges` | `float\|null` | `exchangeRateChanges` |
| `cashAndCashEquivalentsChanges` | `float\|null` | `cashAndCashEquivalentsChanges` |
| `changeInWorkingCapital` | `float\|null` | `changeInWorkingCapital` |
| `stockBasedCompensation` | `float\|null` | `stockBasedCompensation` |
| `otherNonCashItems` | `float\|null` | `otherNonCashItems` |
| `freeCashFlow` | `float\|null` | `freeCashFlow` |
