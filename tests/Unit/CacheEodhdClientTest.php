<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\EodhdClient\CacheEodhdClient;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CacheEodhdClientTest extends TestCase
{

	public function testListIsServedFromCache(): void
	{
		// the mock answers one request only, so a second HTTP request would fail
		$client = new CacheEodhdClient(
			$this->createClient('div-CEZ.PR.json'),
			$cache = new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$first = iterator_to_array($client->dividends('CEZ.PR'), false);
		self::assertTrue($cache->has('eodhd-client.v1.dividends.CEZ.PR.any.any'));

		$second = iterator_to_array($client->dividends('CEZ.PR'), false);

		self::assertCount(22, $second);
		self::assertSame($first[0]->toArray(), $second[0]->toArray());
	}

	public function testFundamentalsAreServedFromCache(): void
	{
		$client = new CacheEodhdClient(
			$this->createClient('fundamentals-TLV.RO.json'),
			new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		$first = $client->fundamentals('TLV.RO');
		$second = $client->fundamentals('TLV.RO');

		self::assertNotNull($first);
		self::assertNotNull($second);
		self::assertSame($first->toArray(), $second->toArray());
	}

	public function testMissingFundamentalsAreCached(): void
	{
		$client = new CacheEodhdClient(
			$this->createClientWithResponses([new MockResponse('Symbol not found', ['http_code' => 404])]),
			new Psr16Cache(new ArrayAdapter()),
			3600,
		);

		self::assertNull($client->fundamentals('UNKNOWN.PR'));
		self::assertNull($client->fundamentals('UNKNOWN.PR'));
	}

}
