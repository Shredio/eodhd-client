<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\EodhdClient\Exception\UnexpectedResponseContentException;
use Shredio\EodhdClient\Payload\AnalystRatings;
use Shredio\EodhdClient\Payload\AnnualEarnings;
use Shredio\EodhdClient\Payload\CompanyAddress;
use Shredio\EodhdClient\Payload\CompanyListing;
use Shredio\EodhdClient\Payload\CompanyOfficer;
use Shredio\EodhdClient\Payload\DividendCountByYear;
use Shredio\EodhdClient\Payload\EarningsHistory;
use Shredio\EodhdClient\Payload\EarningsTrend;
use Shredio\EodhdClient\Payload\EsgActivityInvolvement;
use Shredio\EodhdClient\Payload\Holder;
use Shredio\EodhdClient\Payload\InsiderTransaction;
use Shredio\EodhdClient\Payload\OutstandingShares;
use Shredio\EodhdClient\Payload\SharesStats;
use Shredio\EodhdClient\Payload\Technicals;
use Shredio\EodhdClient\Payload\Valuation;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\Mock\TestUnexpectedResponseContentExceptionHandler;
use Tests\TestCase;

final class FundamentalsTest extends TestCase
{

	public function testRomanianBank(): void
	{
		$fundamentals = $this->createClient('fundamentals-TLV.RO.json')->fundamentals('TLV.RO');

		self::assertNotNull($fundamentals);
		self::assertSame('TLV.RO', $fundamentals->symbol);

		$general = $fundamentals->general;
		self::assertSame('TLV', $general->code);
		self::assertSame('Common Stock', $general->type);
		self::assertSame('Banca Transilvania S.A.', $general->name);
		self::assertSame('RO', $general->exchange);
		self::assertSame('RON', $general->currencyCode);
		self::assertSame('RO', $general->countryIso);
		self::assertSame('ROTLVAACNOR1', $general->isin);
		self::assertSame('TLV.RO', $general->primaryTicker);
		self::assertSame('December', $general->fiscalYearEnd);
		self::assertSame('Financial Services', $general->sector);
		self::assertSame('Banks - Regional', $general->industry);
		self::assertSame('Financials', $general->gicSector);
		self::assertSame(13565, $general->fullTimeEmployees);
		self::assertNull($general->logoUrl, 'An empty string is null');
		self::assertNull($general->cusip, 'US-only keys are optional');
		self::assertSame([], $general->listings);
		self::assertSame(
			(new CompanyAddress('Romania', '30-36 Calea Dorobantilor street', 'Cluj-Napoca', null, '400017'))->toArray(),
			$general->addressData?->toArray(),
		);
		self::assertCount(10, $general->officers);
		self::assertSame(
			(new CompanyOfficer('Mr. Omer  Tetik', 'Chief Executive Officer', 1973))->toArray(),
			$general->officers[0]->toArray(),
		);

		$highlights = $fundamentals->highlights;
		self::assertNotNull($highlights);
		self::assertSame(41_629_126_656, $highlights->marketCapitalization);
		self::assertNull($highlights->ebitda);
		self::assertSame(8.5204, $highlights->peRatio);
		self::assertSame(0.0, $highlights->epsEstimateNextQuarter);
		self::assertSame('2026-06-30', $highlights->mostRecentQuarter);
		self::assertSame(11_750_108_160, $highlights->revenueTtm);

		self::assertSame(
			(new Valuation(8.5204, 15.5763, 3.5429, 1.8407, 49_394_221_056, 4.7242, 0.0))->toArray(),
			$fundamentals->valuation?->toArray(),
		);
		self::assertSame(
			(new SharesStats(1_246_380_975, 1_246_422_555, 4.6240000000000006, 31.381999999999998, null, null, null, null, null))->toArray(),
			$fundamentals->sharesStats?->toArray(),
		);
		self::assertSame(
			(new Technicals(0.468, 38.28, 22.9174, 36.2884, 32.6958, 0, 0, 0.0, null))->toArray(),
			$fundamentals->technicals?->toArray(),
		);

		$splitsDividends = $fundamentals->splitsDividends;
		self::assertNotNull($splitsDividends);
		self::assertSame(1.12, $splitsDividends->forwardAnnualDividendRate);
		self::assertNull($splitsDividends->dividendDate);
		self::assertSame('2026-06-15', $splitsDividends->exDividendDate);
		self::assertSame('114.42:100', $splitsDividends->lastSplitFactor);
		self::assertSame('2026-07-16', $splitsDividends->lastSplitDate);
		self::assertCount(12, $splitsDividends->numberDividendsByYear);
		self::assertSame((new DividendCountByYear(2009, 1))->toArray(), $splitsDividends->numberDividendsByYear[0]->toArray());

		self::assertNull($fundamentals->analystRatings);
		self::assertNull($fundamentals->esgScores);
		self::assertNull($fundamentals->holders);
		self::assertSame([], $fundamentals->insiderTransactions);
		self::assertSame([], $fundamentals->annualOutstandingShares);

		self::assertCount(41, $fundamentals->earningsHistory);
		self::assertSame(
			(new EarningsHistory('2026-11-20', '2026-09-30', 'BeforeMarket', null, null, null, 0.0, null))->toArray(),
			$fundamentals->earningsHistory[0]->toArray(),
		);
		self::assertSame(
			(new EarningsHistory('2024-05-10', '2024-03-31', 'AfterMarket', 'RON', 0.08, 0.9, -0.82, -91.1111))->toArray(),
			$fundamentals->earningsHistory[1]->toArray(),
		);
		self::assertCount(11, $fundamentals->earningsTrends);
		self::assertSame(
			(new EarningsTrend(
				date: '2027-12-31',
				period: '+1y',
				growth: -0.0231,
				earningsEstimateAvg: 3.6926,
				earningsEstimateLow: 3.6007,
				earningsEstimateHigh: 3.77,
				earningsEstimateYearAgoEps: 3.78,
				earningsEstimateNumberOfAnalysts: 3.0,
				earningsEstimateGrowth: -0.0231,
				revenueEstimateAvg: 12_869_475_270.0,
				revenueEstimateLow: 11_426_000_000.0,
				revenueEstimateHigh: 13_593_000_000.0,
				revenueEstimateYearAgoEps: null,
				revenueEstimateNumberOfAnalysts: 4.0,
				revenueEstimateGrowth: 0.0565,
				epsTrendCurrent: 3.6926,
				epsTrend7daysAgo: 3.5342,
				epsTrend30daysAgo: 3.5342,
				epsTrend60daysAgo: 3.5313,
				epsTrend90daysAgo: 3.7427,
				epsRevisionsUpLast7days: 1.0,
				epsRevisionsUpLast30days: 1.0,
				epsRevisionsDownLast30days: 0.0,
				epsRevisionsDownLast7days: null,
			))->toArray(),
			$fundamentals->earningsTrends[0]->toArray(),
		);
		self::assertCount(12, $fundamentals->annualEarnings);
		self::assertSame((new AnnualEarnings('2026-09-30', 0.08))->toArray(), $fundamentals->annualEarnings[0]->toArray());
	}

	public function testFinancialStatements(): void
	{
		$statements = $this->createClient('fundamentals-TLV.RO.json')->fundamentals('TLV.RO')?->financialStatements;

		self::assertNotNull($statements);
		self::assertSame('RON', $statements->incomeStatementCurrency);
		self::assertSame('RON', $statements->balanceSheetCurrency);
		self::assertSame('RON', $statements->cashFlowCurrency);
		self::assertCount(7, $statements->annualIncomeStatements);
		self::assertCount(16, $statements->quarterlyIncomeStatements);
		self::assertCount(7, $statements->annualBalanceSheetStatements);
		self::assertCount(16, $statements->quarterlyBalanceSheetStatements);
		self::assertCount(7, $statements->annualCashFlowStatements);
		self::assertCount(16, $statements->quarterlyCashFlowStatements);

		$income = $statements->annualIncomeStatements[0];
		self::assertSame('2025-12-31', $income->date);
		self::assertNull($income->filingDate);
		self::assertSame('RON', $income->currencySymbol);
		self::assertSame(11_839_662_000.0, $income->totalRevenue);
		self::assertSame(4_478_867_000.0, $income->netIncome);
		self::assertSame(-181_854_000.0, $income->minorityInterest);
		self::assertSame(8_065_549_000.0, $income->netInterestIncome);
		self::assertNull($income->grossProfit);
		self::assertSame('2026-06-30', $statements->quarterlyIncomeStatements[0]->date, 'Newest first');

		$balanceSheet = $statements->annualBalanceSheetStatements[0];
		self::assertSame('2025-12-31', $balanceSheet->date);
		self::assertSame(224_413_697_000.0, $balanceSheet->totalAssets);
		self::assertSame(201_115_906_000.0, $balanceSheet->totalLiab);
		self::assertSame(22_410_059_000.0, $balanceSheet->totalStockholderEquity);
		self::assertSame(39_767_598_000.0, $balanceSheet->cash);
		self::assertNull($balanceSheet->capitalSurplus, 'Renamed from the misspelled capitalSurpluse');

		$cashFlow = $statements->quarterlyCashFlowStatements[0];
		self::assertSame('2026-06-30', $cashFlow->date);
		self::assertSame(-6_769_985_000.0, $cashFlow->totalCashFromOperatingActivities);
		self::assertSame(225_468_000.0, $cashFlow->capitalExpenditures, 'EODHD reports capital expenditures as a positive amount');
		self::assertSame(-6_995_453_000.0, $cashFlow->freeCashFlow);
		self::assertSame(1_193_616_000.0, $cashFlow->dividendsPaid);
	}

	public function testUsListingSections(): void
	{
		$fundamentals = $this->createClient('fundamentals-AAPL.US.json')->fundamentals('AAPL.US');

		self::assertNotNull($fundamentals);
		self::assertSame('037833100', $fundamentals->general->cusip);
		self::assertSame('Domestic', $fundamentals->general->homeCategory);
		self::assertFalse($fundamentals->general->isDelisted);
		self::assertSame('September', $fundamentals->general->fiscalYearEnd);
		self::assertCount(3, $fundamentals->general->listings);
		self::assertSame((new CompanyListing('0R2V', 'LSE', 'Apple Inc.'))->toArray(), $fundamentals->general->listings[0]->toArray());

		self::assertSame(
			(new AnalystRatings(4.0417, 328.2221, 23, 7, 16, 1, 1))->toArray(),
			$fundamentals->analystRatings?->toArray(),
		);

		$esgScores = $fundamentals->esgScores;
		self::assertNotNull($esgScores);
		self::assertSame(26.15, $esgScores->totalEsg);
		self::assertSame(3, $esgScores->controversyLevel);
		self::assertCount(15, $esgScores->activitiesInvolvement);
		self::assertSame((new EsgActivityInvolvement('adult', 'No'))->toArray(), $esgScores->activitiesInvolvement[0]->toArray());

		$holders = $fundamentals->holders;
		self::assertNotNull($holders);
		self::assertCount(20, $holders->institutions);
		self::assertCount(10, $holders->funds);
		self::assertSame(
			(new Holder('BlackRock Inc', '2026-03-31', 7.8435, 5.0758, 1_144_695_425, -9_970_306, -0.8635))->toArray(),
			$holders->institutions[0]->toArray(),
		);

		self::assertCount(42, $fundamentals->annualOutstandingShares);
		self::assertCount(164, $fundamentals->quarterlyOutstandingShares);
		self::assertSame(
			(new OutstandingShares('2026-Q2', '2026-06-30', 14_750.302, 14_750_302_000))->toArray(),
			$fundamentals->quarterlyOutstandingShares[0]->toArray(),
		);

		$statements = $fundamentals->financialStatements;
		self::assertCount(41, $statements->annualIncomeStatements);
		self::assertCount(164, $statements->quarterlyIncomeStatements);
		self::assertCount(37, $statements->annualCashFlowStatements);
		self::assertSame('2025-10-31', $statements->annualIncomeStatements[0]->filingDate);
		self::assertSame(416_161_000_000.0, $statements->annualIncomeStatements[0]->totalRevenue);
	}

	public function testInsiderTransactions(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		$values['InsiderTransactions'] = [
			'0' => [
				'date' => '2026-03-30',
				'ownerCik' => null,
				'ownerName' => 'Jane Doe',
				'transactionDate' => '2026-03-30',
				'transactionCode' => 'P',
				'transactionAmount' => 1500,
				'transactionPrice' => 358.96,
				'transactionAcquiredDisposed' => 'A',
				'postTransactionAmount' => null,
				'secLink' => null,
			],
		];

		$fundamentals = $this->createClientWithResponses([new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))])
			->fundamentals('TLV.RO');

		self::assertNotNull($fundamentals);
		self::assertSame(
			(new InsiderTransaction('2026-03-30', null, 'Jane Doe', '2026-03-30', 'P', 1500, 358.96, 'A', null, null))->toArray(),
			$fundamentals->insiderTransactions[0]->toArray(),
		);
	}

	public function testWithoutFinancialStatements(): void
	{
		$fundamentals = $this->createClient('fundamentals-BEZVA.PR.json')->fundamentals('BEZVA.PR');

		self::assertNotNull($fundamentals);
		self::assertSame('Bezvavlasy as', $fundamentals->general->name);
		self::assertNull($fundamentals->financialStatements->incomeStatementCurrency);
		self::assertSame([], $fundamentals->financialStatements->annualIncomeStatements);
		self::assertSame([], $fundamentals->financialStatements->quarterlyIncomeStatements);
		self::assertSame([], $fundamentals->financialStatements->annualBalanceSheetStatements);
		self::assertSame([], $fundamentals->financialStatements->quarterlyCashFlowStatements);
	}

	public function testEmptyResponseIsNull(): void
	{
		// e.g. PTENGETF.RO, an ETF without fundamentals
		$client = $this->createClientWithResponses([new MockResponse('[]')]);

		self::assertNull($client->fundamentals('PTENGETF.RO'));
	}

	public function testUnknownSymbolIsNull(): void
	{
		$client = $this->createClientWithResponses([new MockResponse('Symbol not found', ['http_code' => 404])]);

		self::assertNull($client->fundamentals('UNKNOWN.PR'));
	}

	public function testDriftedSectionIsDroppedAndTheRestKept(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		self::assertIsArray($values['Highlights']);
		$values['Highlights']['PERatio'] = 'not a number';

		$handler = new TestUnexpectedResponseContentExceptionHandler();
		$fundamentals = $this->createClientWithResponses(
			[new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))],
			$handler,
			strictMode: false,
		)->fundamentals('TLV.RO');

		self::assertNotNull($fundamentals);
		self::assertNull($fundamentals->highlights);
		self::assertNotNull($fundamentals->valuation);
		self::assertCount(7, $fundamentals->financialStatements->annualIncomeStatements);
		self::assertCount(1, $handler->exceptions);
		self::assertFalse($handler->exceptions[0]->noticesOnly);
		self::assertStringContainsString('PERatio', $handler->messages[0]);
		self::assertSame('https://eodhd.com/api/fundamentals/TLV.RO', $handler->exceptions[0]->url);
	}

	public function testDriftedStatementRowIsDroppedAndTheOthersKept(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		self::assertIsArray($values['Financials']);
		self::assertIsArray($values['Financials']['Income_Statement']);
		self::assertIsArray($values['Financials']['Income_Statement']['yearly']);
		self::assertIsArray($values['Financials']['Income_Statement']['yearly']['2024-12-31']);
		$values['Financials']['Income_Statement']['yearly']['2024-12-31']['totalRevenue'] = 'N/A';

		$handler = new TestUnexpectedResponseContentExceptionHandler();
		$fundamentals = $this->createClientWithResponses(
			[new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))],
			$handler,
			strictMode: false,
		)->fundamentals('TLV.RO');

		self::assertNotNull($fundamentals);
		$dates = array_map(
			static fn ($statement): string => $statement->date,
			$fundamentals->financialStatements->annualIncomeStatements,
		);
		self::assertNotContains('2024-12-31', $dates);
		self::assertCount(6, $dates);
		self::assertCount(1, $handler->exceptions);
	}

	public function testUnknownSectionIsNotice(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		$values['ETF_Data'] = ['Holdings' => []];

		$handler = new TestUnexpectedResponseContentExceptionHandler();
		$fundamentals = $this->createClientWithResponses(
			[new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))],
			$handler,
			strictMode: false,
		)->fundamentals('TLV.RO');

		self::assertNotNull($fundamentals);
		self::assertCount(1, $handler->exceptions);
		self::assertTrue($handler->exceptions[0]->noticesOnly);
		self::assertStringContainsString('ETF_Data', $handler->messages[0]);
	}

	public function testUnknownSectionThrowsInStrictMode(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		$values['ETF_Data'] = ['Holdings' => []];

		$this->expectException(UnexpectedResponseContentException::class);
		$this->expectExceptionMessage('unknown sections ETF_Data');

		$this->createClientWithResponses([new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))])
			->fundamentals('TLV.RO');
	}

	public function testUnknownKeyThrowsInStrictMode(): void
	{
		$values = $this->readFixture('fundamentals-TLV.RO.json');
		self::assertIsArray($values['Valuation']);
		$values['Valuation']['NewRatio'] = 1.5;

		$this->expectException(UnexpectedResponseContentException::class);
		$this->expectExceptionMessage('NewRatio');

		$this->createClientWithResponses([new MockResponse(json_encode($values, JSON_THROW_ON_ERROR))])
			->fundamentals('TLV.RO');
	}

}
