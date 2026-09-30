<?php declare(strict_types = 1);

namespace Tests\Unit;

use DateTimeImmutable;
use Shredio\EodhdClient\Payload\EarningsCalendarItem;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class CalendarTest extends TestCase
{

	public function testEarningsCalendar(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return MockResponse::fromFile(sprintf('%s/calendar-earnings-symbols.json', self::FixturesDirectory));
		});

		$earnings = iterator_to_array($client->earningsCalendar(
			new DateTimeImmutable('2025-01-01'),
			new DateTimeImmutable('2026-12-31'),
			['TLV.RO', 'SNP.RO', 'CEZ.PR'],
		), false);

		self::assertSame(
			['https://eodhd.com/api/calendar/earnings?from=2025-01-01&to=2026-12-31&symbols=TLV.RO%2CSNP.RO%2CCEZ.PR&api_token=SECRET&fmt=json'],
			$requestedUrls,
		);
		self::assertCount(9, $earnings);
		self::assertSame(
			(new EarningsCalendarItem('SNP.RO', '2025-04-30', '2025-03-31', 'BeforeMarket', 'RON', 0.02, 0.02, 0.0, 0.0))->toArray(),
			$earnings[0]->toArray(),
		);
		self::assertSame(
			(new EarningsCalendarItem('SNP.RO', '2026-02-03', '2025-12-31', 'AfterMarket', null, 0.02, -0.01, 0.03, 300.0))->toArray(),
			$earnings[1]->toArray(),
		);
	}

	public function testEarningsTrendsAreFlattened(): void
	{
		$trends = iterator_to_array($this->createClient('calendar-trends.json')->earningsTrends(['TLV.RO', 'AAPL.US']), false);

		// 19 periods of TLV.RO followed by 96 of AAPL.US
		self::assertCount(115, $trends);
		self::assertSame('TLV.RO', $trends[0]->code);
		self::assertSame('+1y', $trends[0]->period);
		self::assertSame(3.6926, $trends[0]->earningsEstimateAvg);
		self::assertSame(12_869_475_270.0, $trends[0]->revenueEstimateAvg);
		self::assertNull($trends[0]->epsRevisionsDownLast7days, 'Returned by fundamentals only');
		self::assertSame('AAPL.US', $trends[19]->code);
	}

}
