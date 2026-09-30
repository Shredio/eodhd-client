<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\EodhdClient\Enum\PricePeriod;
use Shredio\EodhdClient\Payload\BulkEndOfDayPrice;
use Shredio\EodhdClient\Payload\EndOfDayPrice;
use Shredio\EodhdClient\Payload\RealTimeQuote;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class PriceTest extends TestCase
{

	public function testEndOfDayPrices(): void
	{
		$prices = iterator_to_array($this->createClient('eod-CEZ.PR.json')->endOfDayPrices('CEZ.PR'), false);

		self::assertCount(20, $prices);
		self::assertSame(
			(new EndOfDayPrice('2026-09-01', 1380.0, 1384.0, 1359.0, 1372.0, 1372.0, 65776))->toArray(),
			$prices[0]->toArray(),
		);
	}

	public function testEndOfDayPricesQuery(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(static function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return new MockResponse('[]');
		});

		iterator_to_array($client->endOfDayPrices(
			'CEZ.PR',
			new DateTimeImmutable('2026-09-01'),
			new DateTimeImmutable('2026-09-29'),
			PricePeriod::Weekly,
		));

		self::assertSame(
			['https://eodhd.com/api/eod/CEZ.PR?from=2026-09-01&to=2026-09-29&period=w&api_token=SECRET&fmt=json'],
			$requestedUrls,
		);
	}

	public function testUnknownSymbolHasNoPrices(): void
	{
		$client = $this->createClientWithResponses([new MockResponse('Ticker Not Found.', ['http_code' => 404])]);

		self::assertSame([], iterator_to_array($client->endOfDayPrices('UNKNOWN.PR')));
	}

	public function testBulkEndOfDayPrices(): void
	{
		$prices = iterator_to_array(
			$this->createClient('eod-bulk-PR.json')->bulkEndOfDayPrices('PR', new DateTimeImmutable('2026-09-29')),
			false,
		);

		self::assertCount(45, $prices);
		self::assertSame(
			(new BulkEndOfDayPrice('CEZ', 'PR', '2026-09-29', 1350.0, 1354.0, 1329.0, 1342.0, 1342.0, 160995))->toArray(),
			$prices[0]->toArray(),
		);
	}

	public function testSingleRealTimeQuote(): void
	{
		$quotes = iterator_to_array($this->createClient('real-time-single.json')->realTimeQuotes(['CEZ.PR']), false);

		self::assertCount(1, $quotes);
		self::assertSame(
			(new RealTimeQuote('CEZ.PR', 1790777820, 0, 1335.0, 1347.0, 1335.0, 1345.0, 76337, 1342.0, 3.0, 0.2235))->toArray(),
			$quotes[0]->toArray(),
		);
	}

	public function testRealTimeQuotesWithUnknownSymbol(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return MockResponse::fromFile(sprintf('%s/real-time-with-missing.json', self::FixturesDirectory));
		});

		$quotes = iterator_to_array(
			$client->realTimeQuotes(['AAPL.US', 'MSFT.US', 'SAP.XETRA', 'XXXXNOPE.PR', 'CEZ.PR']),
			false,
		);

		self::assertSame(
			['https://eodhd.com/api/real-time/AAPL.US?s=MSFT.US%2CSAP.XETRA%2CXXXXNOPE.PR%2CCEZ.PR&api_token=SECRET&fmt=json'],
			$requestedUrls,
		);
		self::assertCount(5, $quotes);
		self::assertSame(
			(new RealTimeQuote('XXXXNOPE.PR', null, 0, null, null, null, null, null, null, null, null))->toArray(),
			$quotes[3]->toArray(),
		);
		self::assertSame(337.285, $quotes[0]->close);
	}

}
