<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\EodhdClient\Payload\BulkDividend;
use Shredio\EodhdClient\Payload\BulkSplit;
use Shredio\EodhdClient\Payload\Dividend;
use Shredio\EodhdClient\Payload\Split;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CorporateActionTest extends TestCase
{

	public function testDividends(): void
	{
		$dividends = iterator_to_array($this->createClient('div-CEZ.PR.json')->dividends('CEZ.PR'), false);

		self::assertCount(22, $dividends);
		self::assertSame(
			(new Dividend('2005-06-21', null, null, '2005-08-01', null, 9.0, 9.0, 'CZK'))->toArray(),
			$dividends[0]->toArray(),
		);
		self::assertSame(
			(new Dividend('2026-06-04', null, null, '2026-08-03', null, 42.0, 42.0, 'CZK'))->toArray(),
			$dividends[21]->toArray(),
		);
	}

	public function testDividendsWithAllDates(): void
	{
		$dividends = iterator_to_array(
			$this->createClient('div-AAPL.US.json')->dividends('AAPL.US', new DateTimeImmutable('2024-01-01')),
			false,
		);

		self::assertCount(11, $dividends);
		self::assertSame(
			(new Dividend('2024-02-09', '2024-02-01', '2024-02-12', '2024-02-15', 'Quarterly', 0.24, 0.24, 'USD'))->toArray(),
			$dividends[0]->toArray(),
		);
	}

	public function testUnknownSymbolHasNoDividends(): void
	{
		$client = $this->createClientWithResponses([new MockResponse('Symbol not found', ['http_code' => 404])]);

		self::assertSame([], iterator_to_array($client->dividends('UNKNOWN.PR')));
	}

	public function testBulkDividends(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return MockResponse::fromFile(sprintf('%s/eod-bulk-US-dividends.json', self::FixturesDirectory));
		});

		$dividends = iterator_to_array($client->bulkDividends('US', new DateTimeImmutable('2026-09-29')), false);

		self::assertSame(
			['https://eodhd.com/api/eod-bulk-last-day/US?type=dividends&date=2026-09-29&api_token=SECRET&fmt=json'],
			$requestedUrls,
		);
		self::assertCount(517, $dividends);
		self::assertSame(
			(new BulkDividend('ABLD', 'US', '2026-09-29', 0.208, 'USD', null, '2026-09-29', '2026-09-30', null, 0.208))->toArray(),
			$dividends[0]->toArray(),
		);
	}

	public function testSplitsIncludeBonusShares(): void
	{
		$splits = iterator_to_array($this->createClient('splits-TLV.RO.json')->splits('TLV.RO'), false);

		self::assertCount(18, $splits);
		self::assertSame((new Split('2008-05-14', '1.684140/1.000000'))->toArray(), $splits[0]->toArray());
		self::assertSame((new Split('2009-01-07', '1.000000/10.000000'))->toArray(), $splits[1]->toArray());
	}

	public function testBulkSplits(): void
	{
		$splits = iterator_to_array($this->createClient('eod-bulk-US-splits.json')->bulkSplits('US'), false);

		self::assertCount(15, $splits);
		self::assertSame((new BulkSplit('AGRZ', 'US', '2026-09-29', '1.000000/20.000000'))->toArray(), $splits[0]->toArray());
	}

}
