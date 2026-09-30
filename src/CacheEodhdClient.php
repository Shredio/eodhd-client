<?php declare(strict_types = 1);

namespace Shredio\EodhdClient;

use DateInterval;
use DateTimeInterface;
use Psr\SimpleCache\CacheInterface;
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
 * Caches per-symbol and list endpoints; delegates bulk, calendar, real-time and account endpoints directly to the
 * inner client, because their results change during the day.
 */
final readonly class CacheEodhdClient implements EodhdClient
{

	/**
	 * Part of every cache key. Bump it whenever a cached payload changes shape: an entry serialized with the old
	 * shape would be unserialized with the new properties uninitialized.
	 */
	private const int CacheKeyVersion = 1;

	private const string DateFormat = 'Y-m-d';

	/**
	 * @param int<1, max>|DateInterval|null $ttl Time to live for cached items in seconds
	 */
	public function __construct(
		private EodhdClient $client,
		private CacheInterface $cache,
		private int|DateInterval|null $ttl,
	)
	{
	}

	public function withStrictMode(bool $strictMode): EodhdClient
	{
		return new self(
			$this->client->withStrictMode($strictMode),
			$this->cache,
			$this->ttl,
		);
	}

	public function withRetryConfiguration(HttpClientRetryConfiguration $config): EodhdClient
	{
		return new self(
			$this->client->withRetryConfiguration($config),
			$this->cache,
			$this->ttl,
		);
	}

	public function forBackgroundProcessing(): EodhdClient
	{
		return new self(
			$this->client->forBackgroundProcessing(),
			$this->cache,
			$this->ttl,
		);
	}

	/**
	 * @template TReturn
	 * @param callable(): TReturn $fn
	 * @return EodhdPromise<TReturn>
	 */
	public function promise(callable $fn): EodhdPromise
	{
		return $this->client->promise($fn);
	}

	public function user(): ?User
	{
		return $this->client->user();
	}

	/**
	 * @return iterable<int, Exchange>
	 */
	public function exchangesList(): iterable
	{
		return $this->cached(__FUNCTION__, fn () => $this->client->exchangesList());
	}

	/**
	 * @return iterable<int, ExchangeSymbol>
	 */
	public function exchangeSymbolList(string $exchange, bool $delisted = false): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->exchangeSymbolList($exchange, $delisted),
			sprintf('%s.%s', $exchange, $delisted ? 'delisted' : 'listed'),
		);
	}

	public function fundamentals(string $symbol): ?Fundamentals
	{
		return $this->cachedNullable(__FUNCTION__, fn () => $this->client->fundamentals($symbol), $symbol);
	}

	/**
	 * @return iterable<int, EndOfDayPrice>
	 */
	public function endOfDayPrices(
		string $symbol,
		?DateTimeInterface $from = null,
		?DateTimeInterface $to = null,
		PricePeriod $period = PricePeriod::Daily,
	): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->endOfDayPrices($symbol, $from, $to, $period),
			sprintf('%s.%s.%s.%s', $symbol, $this->dateKey($from), $this->dateKey($to), $period->value),
		);
	}

	/**
	 * @return iterable<int, BulkEndOfDayPrice>
	 */
	public function bulkEndOfDayPrices(string $exchange, ?DateTimeInterface $date = null, array $symbols = []): iterable
	{
		return $this->client->bulkEndOfDayPrices($exchange, $date, $symbols);
	}

	/**
	 * @return iterable<int, RealTimeQuote>
	 */
	public function realTimeQuotes(array $symbols): iterable
	{
		return $this->client->realTimeQuotes($symbols);
	}

	/**
	 * @return iterable<int, Dividend>
	 */
	public function dividends(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->dividends($symbol, $from, $to),
			sprintf('%s.%s.%s', $symbol, $this->dateKey($from), $this->dateKey($to)),
		);
	}

	/**
	 * @return iterable<int, BulkDividend>
	 */
	public function bulkDividends(string $exchange, ?DateTimeInterface $date = null): iterable
	{
		return $this->client->bulkDividends($exchange, $date);
	}

	/**
	 * @return iterable<int, Split>
	 */
	public function splits(string $symbol, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): iterable
	{
		return $this->cached(
			__FUNCTION__,
			fn () => $this->client->splits($symbol, $from, $to),
			sprintf('%s.%s.%s', $symbol, $this->dateKey($from), $this->dateKey($to)),
		);
	}

	/**
	 * @return iterable<int, BulkSplit>
	 */
	public function bulkSplits(string $exchange, ?DateTimeInterface $date = null): iterable
	{
		return $this->client->bulkSplits($exchange, $date);
	}

	/**
	 * @return iterable<int, EarningsCalendarItem>
	 */
	public function earningsCalendar(?DateTimeInterface $from = null, ?DateTimeInterface $to = null, array $symbols = []): iterable
	{
		return $this->client->earningsCalendar($from, $to, $symbols);
	}

	/**
	 * @return iterable<int, EarningsTrend>
	 */
	public function earningsTrends(array $symbols): iterable
	{
		return $this->client->earningsTrends($symbols);
	}

	/**
	 * @param array<string, scalar|null> $query
	 */
	public function request(string $path, array $query = []): ResponseInterface
	{
		return $this->client->request($path, $query);
	}

	/**
	 * @template T
	 * @param non-empty-string $method
	 * @param callable(): iterable<int, T> $factory
	 * @return list<T>
	 */
	private function cached(string $method, callable $factory, ?string $suffix = null): array
	{
		$cacheKey = $this->key($method, $suffix);
		/** @var list<T>|null $value */
		$value = $this->cache->get($cacheKey);

		if ($value === null) {
			$value = iterator_to_array($factory(), false);

			$this->cache->set($cacheKey, $value, $this->ttl);
		}

		return $value;
	}

	/**
	 * @template T of object
	 * @param non-empty-string $method
	 * @param callable(): (T|null) $factory
	 * @return T|null
	 */
	private function cachedNullable(string $method, callable $factory, ?string $suffix = null): ?object
	{
		$cacheKey = $this->key($method, $suffix);
		/** @var array{hit: true, value: T|null}|null $cached */
		$cached = $this->cache->get($cacheKey);

		if ($cached === null) {
			$value = $factory();

			$this->cache->set($cacheKey, ['hit' => true, 'value' => $value], $this->ttl);

			return $value;
		}

		return $cached['value'];
	}

	private function dateKey(?DateTimeInterface $date): string
	{
		return $date?->format(self::DateFormat) ?? 'any';
	}

	/**
	 * PSR-16 reserves the characters {}()/\@: in keys; they are replaced, because symbols and exchange codes come
	 * from the caller.
	 *
	 * @param non-empty-string $method
	 */
	private function key(string $method, ?string $suffix = null): string
	{
		$key = sprintf('eodhd-client.v%d.%s', self::CacheKeyVersion, $method);
		if ($suffix !== null && $suffix !== '') {
			$key = sprintf('%s.%s', $key, $suffix);
		}

		return strtr($key, '{}()/\\@:', '________');
	}

}
