<?php declare(strict_types = 1);

namespace Shredio\EodhdClient;

use DateTimeInterface;
use SensitiveParameter;
use Shredio\EodhdClient\Config\HttpClientRetryConfiguration;
use Shredio\EodhdClient\Converter\RepresentableNumberConverter;
use Shredio\EodhdClient\Enum\PricePeriod;
use Shredio\EodhdClient\Exception\UnexpectedHttpCodeException;
use Shredio\EodhdClient\Exception\UnexpectedResponseContentException;
use Shredio\EodhdClient\Exception\UnexpectedResponseContentExceptionHandler;
use Shredio\EodhdClient\Mapper\AnalystRatingsMapper;
use Shredio\EodhdClient\Mapper\AnnualEarningsMapper;
use Shredio\EodhdClient\Mapper\BalanceSheetStatementMapper;
use Shredio\EodhdClient\Mapper\BulkDividendMapper;
use Shredio\EodhdClient\Mapper\BulkEndOfDayPriceMapper;
use Shredio\EodhdClient\Mapper\BulkSplitMapper;
use Shredio\EodhdClient\Mapper\CashFlowStatementMapper;
use Shredio\EodhdClient\Mapper\CompanyGeneralMapper;
use Shredio\EodhdClient\Mapper\DividendMapper;
use Shredio\EodhdClient\Mapper\EarningsCalendarItemMapper;
use Shredio\EodhdClient\Mapper\EarningsHistoryMapper;
use Shredio\EodhdClient\Mapper\EarningsTrendMapper;
use Shredio\EodhdClient\Mapper\EndOfDayPriceMapper;
use Shredio\EodhdClient\Mapper\EsgScoresMapper;
use Shredio\EodhdClient\Mapper\ExchangeMapper;
use Shredio\EodhdClient\Mapper\ExchangeSymbolMapper;
use Shredio\EodhdClient\Mapper\HighlightsMapper;
use Shredio\EodhdClient\Mapper\HoldersMapper;
use Shredio\EodhdClient\Mapper\IncomeStatementMapper;
use Shredio\EodhdClient\Mapper\InsiderTransactionMapper;
use Shredio\EodhdClient\Mapper\OutstandingSharesMapper;
use Shredio\EodhdClient\Mapper\RealTimeQuoteMapper;
use Shredio\EodhdClient\Mapper\SharesStatsMapper;
use Shredio\EodhdClient\Mapper\SplitMapper;
use Shredio\EodhdClient\Mapper\SplitsDividendsMapper;
use Shredio\EodhdClient\Mapper\TechnicalsMapper;
use Shredio\EodhdClient\Mapper\UserMapper;
use Shredio\EodhdClient\Mapper\ValuationMapper;
use Shredio\EodhdClient\Parser\LargeResponseParser;
use Shredio\EodhdClient\Payload\AnalystRatings;
use Shredio\EodhdClient\Payload\AnnualEarnings;
use Shredio\EodhdClient\Payload\BalanceSheetStatement;
use Shredio\EodhdClient\Payload\BulkDividend;
use Shredio\EodhdClient\Payload\BulkEndOfDayPrice;
use Shredio\EodhdClient\Payload\BulkSplit;
use Shredio\EodhdClient\Payload\CashFlowStatement;
use Shredio\EodhdClient\Payload\CompanyGeneral;
use Shredio\EodhdClient\Payload\Dividend;
use Shredio\EodhdClient\Payload\EarningsCalendarItem;
use Shredio\EodhdClient\Payload\EarningsHistory;
use Shredio\EodhdClient\Payload\EarningsTrend;
use Shredio\EodhdClient\Payload\EndOfDayPrice;
use Shredio\EodhdClient\Payload\EsgScores;
use Shredio\EodhdClient\Payload\Exchange;
use Shredio\EodhdClient\Payload\ExchangeSymbol;
use Shredio\EodhdClient\Payload\FinancialStatements;
use Shredio\EodhdClient\Payload\Fundamentals;
use Shredio\EodhdClient\Payload\Highlights;
use Shredio\EodhdClient\Payload\Holders;
use Shredio\EodhdClient\Payload\IncomeStatement;
use Shredio\EodhdClient\Payload\InsiderTransaction;
use Shredio\EodhdClient\Payload\OutstandingShares;
use Shredio\EodhdClient\Payload\RealTimeQuote;
use Shredio\EodhdClient\Payload\SharesStats;
use Shredio\EodhdClient\Payload\Split;
use Shredio\EodhdClient\Payload\SplitsDividends;
use Shredio\EodhdClient\Payload\Technicals;
use Shredio\EodhdClient\Payload\User;
use Shredio\EodhdClient\Payload\Valuation;
use Shredio\EodhdClient\Promise\EodhdPromise;
use Shredio\TypeSchema\Config\TypeConfig;
use Shredio\TypeSchema\Context\SourceFormat;
use Shredio\TypeSchema\Conversion\ConfigurableConversionStrategy;
use Shredio\TypeSchema\Conversion\Converter\Array\LenientArrayConverter;
use Shredio\TypeSchema\Conversion\Converter\Bool\StrictBoolConverter;
use Shredio\TypeSchema\Conversion\Converter\Null\LenientNullConverter;
use Shredio\TypeSchema\Conversion\Converter\Number\LenientNumberConverter;
use Shredio\TypeSchema\Conversion\Converter\String\StrictStringConverter;
use Shredio\TypeSchema\Conversion\Object\LenientObjectSupervisor;
use Shredio\TypeSchema\Issue\Report\TypeSchemaErrorFormatter;
use Shredio\TypeSchema\Result\Failure;
use Shredio\TypeSchema\Types\Type;
use Shredio\TypeSchema\TypeSchemaProcessor;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\Retry\GenericRetryStrategy;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class SymfonyEodhdClient implements EodhdClient
{

	private const string BaseUrl = 'https://eodhd.com/api/';

	private const string DateFormat = 'Y-m-d';

	private const int ErrorBodyExcerptLength = 200;

	/**
	 * Top-level sections of a fundamentals response the client maps. Any other section (e.g. `ETF_Data`) is reported
	 * as a notice, or as an error in strict mode.
	 */
	private const array FundamentalsSections = [
		'General' => true,
		'Highlights' => true,
		'Valuation' => true,
		'SharesStats' => true,
		'Technicals' => true,
		'SplitsDividends' => true,
		'AnalystRatings' => true,
		'ESGScores' => true,
		'Holders' => true,
		'InsiderTransactions' => true,
		'outstandingShares' => true,
		'Earnings' => true,
		'Financials' => true,
	];

	private LargeResponseParser $largeResponseParser;

	private TypeSchemaProcessor $schemaProcessor;

	private TypeConfig $typeConfig;

	private HttpClientInterface $httpClient;

	public function __construct(
		HttpClientInterface $httpClient,
		#[SensitiveParameter]
		private string $secret,
		private ?UnexpectedResponseContentExceptionHandler $invalidArgumentHandler = null,
		private bool $strictMode = false,
		?HttpClientRetryConfiguration $retryConfiguration = new HttpClientRetryConfiguration(),
	)
	{
		if ($retryConfiguration === null) {
			$this->httpClient = $httpClient;
		} else {
			$this->httpClient = new RetryableHttpClient(
				$httpClient,
				new GenericRetryStrategy(delayMs: $retryConfiguration->delayMs, multiplier: $retryConfiguration->multiplier),
				$retryConfiguration->maxRetries,
			);
		}

		$this->largeResponseParser = new LargeResponseParser();
		$this->schemaProcessor = TypeSchemaProcessor::createDefault();
		// EODHD sends many numbers as strings ("66894000000.00" in statements, "0.20800" in bulk dividends)
		$this->typeConfig = new TypeConfig(new ConfigurableConversionStrategy(
			new StrictStringConverter(),
			new RepresentableNumberConverter(new LenientNumberConverter()),
			new StrictBoolConverter(),
			new LenientNullConverter(),
			new LenientArrayConverter(),
			new LenientObjectSupervisor(),
		), options: [
			SourceFormat::class => new SourceFormat('json'),
		]);
	}

	public function withStrictMode(bool $strictMode): self
	{
		return new self(
			$this->httpClient,
			$this->secret,
			$this->invalidArgumentHandler,
			$strictMode,
			retryConfiguration: null,
		);
	}

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): self
	{
		return new self(
			$this->httpClient,
			$this->secret,
			$this->invalidArgumentHandler,
			$this->strictMode,
			retryConfiguration: $config,
		);
	}

	/**
	 * Returns a client configured for background tasks (cron jobs, queues) with extended retry settings and more host connections.
	 */
	public function forBackgroundProcessing(): self
	{
		return new self(
			HttpClient::create(maxHostConnections: 100),
			$this->secret,
			$this->invalidArgumentHandler,
			$this->strictMode,
			retryConfiguration: new HttpClientRetryConfiguration(1000, 1.5, 4),
		);
	}

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return EodhdPromise<TReturn>
	 */
	public function promise(callable $fn): EodhdPromise
	{
		return EodhdPromise::run($fn);
	}

	/**
	 * @see https://eodhd.com/api/user
	 */
	public function user(): ?User
	{
		$url = $this->buildUrlWithoutApiToken('user');

		return $this->map(User::class, new UserMapper(), $this->requestDocument('user', [], $url), $url);
	}

	/**
	 * @see https://eodhd.com/api/exchanges-list/
	 * @return iterable<int, Exchange>
	 */
	public function exchangesList(): iterable
	{
		return $this->requestList(Exchange::class, new ExchangeMapper(), 'exchanges-list/');
	}

	/**
	 * @see https://eodhd.com/api/exchange-symbol-list/{EXCHANGE}
	 * @return iterable<int, ExchangeSymbol>
	 */
	public function exchangeSymbolList(string $exchange, bool $delisted = false): iterable
	{
		return $this->requestList(
			ExchangeSymbol::class,
			new ExchangeSymbolMapper(),
			sprintf('exchange-symbol-list/%s', $exchange),
			['delisted' => $delisted ? '1' : null],
		);
	}

	/**
	 * @see https://eodhd.com/api/fundamentals/{SYMBOL}
	 */
	public function fundamentals(string $symbol): ?Fundamentals
	{
		$path = sprintf('fundamentals/%s', $symbol);
		$url = $this->buildUrlWithoutApiToken($path);

		$document = $this->requestDocument($path, [], $url, notFoundAsNull: true);
		if ($document === null || $document === []) {
			return null;
		}

		$this->reportUnknownFundamentalsSections($document, $url);

		$general = $this->map(CompanyGeneral::class, new CompanyGeneralMapper(), $document['General'] ?? null, $url);
		if ($general === null) {
			return null;
		}

		return new Fundamentals(
			symbol: $symbol,
			general: $general,
			highlights: $this->mapSection(Highlights::class, new HighlightsMapper(), $document, 'Highlights', $url),
			valuation: $this->mapSection(Valuation::class, new ValuationMapper(), $document, 'Valuation', $url),
			sharesStats: $this->mapSection(SharesStats::class, new SharesStatsMapper(), $document, 'SharesStats', $url),
			technicals: $this->mapSection(Technicals::class, new TechnicalsMapper(), $document, 'Technicals', $url),
			splitsDividends: $this->mapSection(SplitsDividends::class, new SplitsDividendsMapper(), $document, 'SplitsDividends', $url),
			analystRatings: $this->mapSection(AnalystRatings::class, new AnalystRatingsMapper(), $document, 'AnalystRatings', $url),
			esgScores: $this->mapSection(EsgScores::class, new EsgScoresMapper(), $document, 'ESGScores', $url),
			holders: $this->mapSection(Holders::class, new HoldersMapper(), $document, 'Holders', $url),
			insiderTransactions: $this->mapItems(
				InsiderTransaction::class,
				new InsiderTransactionMapper(),
				$this->valueAt($document, 'InsiderTransactions'),
				$url,
			),
			annualOutstandingShares: $this->mapItems(
				OutstandingShares::class,
				new OutstandingSharesMapper(),
				$this->valueAt($document, 'outstandingShares', 'annual'),
				$url,
			),
			quarterlyOutstandingShares: $this->mapItems(
				OutstandingShares::class,
				new OutstandingSharesMapper(),
				$this->valueAt($document, 'outstandingShares', 'quarterly'),
				$url,
			),
			earningsHistory: $this->mapItems(
				EarningsHistory::class,
				new EarningsHistoryMapper(),
				$this->valueAt($document, 'Earnings', 'History'),
				$url,
			),
			earningsTrends: $this->mapItems(
				EarningsTrend::class,
				new EarningsTrendMapper(),
				$this->valueAt($document, 'Earnings', 'Trend'),
				$url,
			),
			annualEarnings: $this->mapItems(
				AnnualEarnings::class,
				new AnnualEarningsMapper(),
				$this->valueAt($document, 'Earnings', 'Annual'),
				$url,
			),
			financialStatements: $this->mapFinancialStatements($document, $url),
		);
	}

	/**
	 * @see https://eodhd.com/api/eod/{SYMBOL}
	 * @return iterable<int, EndOfDayPrice>
	 */
	public function endOfDayPrices(
		string $symbol,
		?DateTimeInterface $from = null,
		?DateTimeInterface $to = null,
		PricePeriod $period = PricePeriod::Daily,
	): iterable
	{
		return $this->requestList(
			EndOfDayPrice::class,
			new EndOfDayPriceMapper(),
			sprintf('eod/%s', $symbol),
			[
				'from' => $from?->format(self::DateFormat),
				'to' => $to?->format(self::DateFormat),
				'period' => $period->value,
			],
			notFoundAsEmpty: true,
		);
	}

	/**
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}
	 * @return iterable<int, BulkEndOfDayPrice>
	 */
	public function bulkEndOfDayPrices(string $exchange, ?DateTimeInterface $date = null, array $symbols = []): iterable
	{
		return $this->requestList(
			BulkEndOfDayPrice::class,
			new BulkEndOfDayPriceMapper(),
			sprintf('eod-bulk-last-day/%s', $exchange),
			[
				'date' => $date?->format(self::DateFormat),
				'symbols' => $symbols === [] ? null : implode(',', $symbols),
			],
		);
	}

	/**
	 * @see https://eodhd.com/api/real-time/{SYMBOL}
	 * @return iterable<int, RealTimeQuote>
	 */
	public function realTimeQuotes(array $symbols): iterable
	{
		$firstSymbol = array_shift($symbols);
		$path = sprintf('real-time/%s', $firstSymbol);
		$query = ['s' => $symbols === [] ? null : implode(',', $symbols)];
		$url = $this->buildUrlWithoutApiToken($path, $query);

		$document = $this->requestDocument($path, $query, $url) ?? [];
		// a single quote is returned as an object, several quotes as a list
		$items = array_is_list($document) ? $document : [$document];

		foreach ($items as $item) {
			$object = $this->map(RealTimeQuote::class, new RealTimeQuoteMapper(), $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * @see https://eodhd.com/api/div/{SYMBOL}
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
	{
		return $this->requestList(
			Dividend::class,
			new DividendMapper(),
			sprintf('div/%s', $symbol),
			[
				'from' => $from?->format(self::DateFormat),
				'to' => $to?->format(self::DateFormat),
			],
			notFoundAsEmpty: true,
		);
	}

	/**
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=dividends
	 * @return iterable<int, BulkDividend>
	 */
	public function bulkDividends(string $exchange, ?DateTimeInterface $date = null): iterable
	{
		return $this->requestList(
			BulkDividend::class,
			new BulkDividendMapper(),
			sprintf('eod-bulk-last-day/%s', $exchange),
			[
				'type' => 'dividends',
				'date' => $date?->format(self::DateFormat),
			],
		);
	}

	/**
	 * @see https://eodhd.com/api/splits/{SYMBOL}
	 * @return iterable<int, Split>
	 */
	public function splits(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
	{
		return $this->requestList(
			Split::class,
			new SplitMapper(),
			sprintf('splits/%s', $symbol),
			[
				'from' => $from?->format(self::DateFormat),
				'to' => $to?->format(self::DateFormat),
			],
			notFoundAsEmpty: true,
		);
	}

	/**
	 * @see https://eodhd.com/api/eod-bulk-last-day/{EXCHANGE}?type=splits
	 * @return iterable<int, BulkSplit>
	 */
	public function bulkSplits(string $exchange, ?DateTimeInterface $date = null): iterable
	{
		return $this->requestList(
			BulkSplit::class,
			new BulkSplitMapper(),
			sprintf('eod-bulk-last-day/%s', $exchange),
			[
				'type' => 'splits',
				'date' => $date?->format(self::DateFormat),
			],
		);
	}

	/**
	 * @see https://eodhd.com/api/calendar/earnings
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(?DateTimeInterface $from = null, ?DateTimeInterface $to = null, array $symbols = []): iterable
	{
		return $this->requestList(
			EarningsCalendarItem::class,
			new EarningsCalendarItemMapper(),
			'calendar/earnings',
			[
				'from' => $from?->format(self::DateFormat),
				'to' => $to?->format(self::DateFormat),
				'symbols' => $symbols === [] ? null : implode(',', $symbols),
			],
			pointer: '/earnings',
		);
	}

	/**
	 * @see https://eodhd.com/api/calendar/trends
	 * @return iterable<int, EarningsTrend>
	 */
	public function earningsTrends(array $symbols): iterable
	{
		return $this->requestList(
			EarningsTrend::class,
			new EarningsTrendMapper(),
			'calendar/trends',
			['symbols' => implode(',', $symbols)],
			// the trends of every symbol form a nested list
			pointer: '/trends/-',
		);
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface
	{
		$query['api_token'] = $this->secret;
		$query['fmt'] = 'json';

		return $this->httpClient->request('GET', sprintf('%s%s', self::BaseUrl, $path), [
			'query' => $query,
		]);
	}

	/**
	 * @template TRet of object
	 * @param class-string<TRet> $payload
	 * @param Type<TRet> $type
	 * @param array<string, scalar|null> $query
	 * @param string $pointer JSON pointer of the list inside the response, the whole response when empty
	 * @return iterable<int, TRet>
	 */
	private function requestList(
		string $payload,
		Type $type,
		string $path,
		array $query = [],
		bool $notFoundAsEmpty = false,
		string $pointer = '',
	): iterable
	{
		$url = $this->buildUrlWithoutApiToken($path, $query);
		$response = $this->request($path, $query);

		EodhdPromise::wait();

		if (!$this->isSuccessful($response, $url, $notFoundAsEmpty)) {
			return;
		}

		foreach ($this->largeResponseParser->parseJson($this->httpClient, $response, $pointer) as $item) {
			$object = $this->map($payload, $type, $item, $url);
			if ($object !== null) {
				yield $object;
			}
		}
	}

	/**
	 * Requests a response that is small enough to be decoded at once.
	 *
	 * @param array<string, scalar|null> $query
	 * @return mixed[]|null Null for an HTTP 404 when `$notFoundAsNull` is set
	 */
	private function requestDocument(string $path, array $query, string $url, bool $notFoundAsNull = false): ?array
	{
		$response = $this->request($path, $query);

		EodhdPromise::wait();

		if (!$this->isSuccessful($response, $url, $notFoundAsNull)) {
			return null;
		}

		return $response->toArray();
	}

	/**
	 * @return bool False for an HTTP 404 when `$notFoundAsMissing` is set; the API answers an unknown symbol with it
	 */
	private function isSuccessful(ResponseInterface $response, string $url, bool $notFoundAsMissing): bool
	{
		$statusCode = $response->getStatusCode();
		if ($statusCode === 200) {
			return true;
		}

		if ($statusCode === 404 && $notFoundAsMissing) {
			$response->cancel();

			return false;
		}

		$body = trim($response->getContent(false));

		throw new UnexpectedHttpCodeException(
			sprintf(
				'Unexpected HTTP status code %d received from %s: %s',
				$statusCode,
				$url,
				substr($body, 0, self::ErrorBodyExcerptLength),
			),
			$statusCode,
			$url,
		);
	}

	/**
	 * @template TRet of object
	 * @param class-string<TRet> $payload
	 * @param Type<TRet> $type
	 * @return TRet|null
	 */
	private function map(string $payload, Type $type, mixed $value, string $url): ?object
	{
		$result = $this->schemaProcessor->parse($value, $type, $this->typeConfig, true);
		if ($this->strictMode === true) {
			// notices, e.g. keys added by the API, are errors in strict mode
			$result = $result->withNoticesAsErrors();
		}

		if ($result instanceof Failure) {
			$exception = new UnexpectedResponseContentException(
				sprintf('%s: %s', $payload, TypeSchemaErrorFormatter::prettyString($result->withNoticesAsErrors(), '')),
				null,
				$url,
			);
			if ($this->strictMode === true) {
				throw $exception;
			}

			$this->invalidArgumentHandler?->handle($exception);
			return null;
		}

		if ($result->notices !== null) {
			$this->invalidArgumentHandler?->handle(new UnexpectedResponseContentException(
				sprintf('%s: %s', $payload, TypeSchemaErrorFormatter::prettyString($result->notices, '')),
				null,
				$url,
				noticesOnly: true,
			));
		}

		return $result->value;
	}

	/**
	 * Maps an optional section of a fundamentals response; a missing or empty section is null.
	 *
	 * @template TRet of object
	 * @param class-string<TRet> $payload
	 * @param Type<TRet> $type
	 * @param mixed[] $document
	 * @return TRet|null
	 */
	private function mapSection(string $payload, Type $type, array $document, string $section, string $url): ?object
	{
		$value = $document[$section] ?? null;
		if ($value === null || $value === []) {
			return null;
		}

		return $this->map($payload, $type, $value, $url);
	}

	/**
	 * Maps the items of a collection one by one, so an item that drifted from the schema does not discard the
	 * others. Fundamentals serialize collections as objects keyed by date or position; the keys are dropped.
	 *
	 * @template TRet of object
	 * @param class-string<TRet> $payload
	 * @param Type<TRet> $type
	 * @return list<TRet>
	 */
	private function mapItems(string $payload, Type $type, mixed $values, string $url): array
	{
		if (!is_array($values)) {
			return [];
		}

		$items = [];
		foreach ($values as $value) {
			$object = $this->map($payload, $type, $value, $url);
			if ($object !== null) {
				$items[] = $object;
			}
		}

		return $items;
	}

	/**
	 * @param mixed[] $document
	 */
	private function mapFinancialStatements(array $document, string $url): FinancialStatements
	{
		return new FinancialStatements(
			incomeStatementCurrency: $this->nonEmptyStringOrNull($this->valueAt($document, 'Financials', 'Income_Statement', 'currency_symbol')),
			balanceSheetCurrency: $this->nonEmptyStringOrNull($this->valueAt($document, 'Financials', 'Balance_Sheet', 'currency_symbol')),
			cashFlowCurrency: $this->nonEmptyStringOrNull($this->valueAt($document, 'Financials', 'Cash_Flow', 'currency_symbol')),
			annualIncomeStatements: $this->mapItems(
				IncomeStatement::class,
				new IncomeStatementMapper(),
				$this->valueAt($document, 'Financials', 'Income_Statement', 'yearly'),
				$url,
			),
			quarterlyIncomeStatements: $this->mapItems(
				IncomeStatement::class,
				new IncomeStatementMapper(),
				$this->valueAt($document, 'Financials', 'Income_Statement', 'quarterly'),
				$url,
			),
			annualBalanceSheetStatements: $this->mapItems(
				BalanceSheetStatement::class,
				new BalanceSheetStatementMapper(),
				$this->valueAt($document, 'Financials', 'Balance_Sheet', 'yearly'),
				$url,
			),
			quarterlyBalanceSheetStatements: $this->mapItems(
				BalanceSheetStatement::class,
				new BalanceSheetStatementMapper(),
				$this->valueAt($document, 'Financials', 'Balance_Sheet', 'quarterly'),
				$url,
			),
			annualCashFlowStatements: $this->mapItems(
				CashFlowStatement::class,
				new CashFlowStatementMapper(),
				$this->valueAt($document, 'Financials', 'Cash_Flow', 'yearly'),
				$url,
			),
			quarterlyCashFlowStatements: $this->mapItems(
				CashFlowStatement::class,
				new CashFlowStatementMapper(),
				$this->valueAt($document, 'Financials', 'Cash_Flow', 'quarterly'),
				$url,
			),
		);
	}

	/**
	 * @param mixed[] $document
	 */
	private function reportUnknownFundamentalsSections(array $document, string $url): void
	{
		$unknownSections = array_keys(array_diff_key($document, self::FundamentalsSections));
		if ($unknownSections === []) {
			return;
		}

		$exception = new UnexpectedResponseContentException(
			sprintf('%s: unknown sections %s', Fundamentals::class, implode(', ', $unknownSections)),
			null,
			$url,
			noticesOnly: !$this->strictMode,
		);
		if ($this->strictMode === true) {
			throw $exception;
		}

		$this->invalidArgumentHandler?->handle($exception);
	}

	/**
	 * @param mixed[] $document
	 */
	private function valueAt(array $document, string ...$path): mixed
	{
		$value = $document;
		foreach ($path as $key) {
			if (!is_array($value)) {
				return null;
			}

			$value = $value[$key] ?? null;
		}

		return $value;
	}

	/**
	 * @return non-empty-string|null
	 */
	private function nonEmptyStringOrNull(mixed $value): ?string
	{
		if (is_string($value) && $value !== '') {
			return $value;
		}

		return null;
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	private function buildUrlWithoutApiToken(string $path, array $query = []): string
	{
		$url = sprintf('%s%s', self::BaseUrl, $path);
		$queryString = http_build_query($query);

		if ($queryString === '') {
			return $url;
		}

		return sprintf('%s?%s', $url, $queryString);
	}

}
