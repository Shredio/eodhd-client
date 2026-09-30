<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

/**
 * A fundamentals response. Every section is mapped on its own, so a section that drifted from the schema is null
 * (or a list without the drifted rows) and reported, while the rest of the response is kept. Sections the API does
 * not return for the instrument (e.g. analyst ratings of a small cap) are null as well.
 *
 * @phpstan-import-type CompanyGeneralArray from CompanyGeneral
 * @phpstan-import-type HighlightsArray from Highlights
 * @phpstan-import-type ValuationArray from Valuation
 * @phpstan-import-type SharesStatsArray from SharesStats
 * @phpstan-import-type TechnicalsArray from Technicals
 * @phpstan-import-type SplitsDividendsArray from SplitsDividends
 * @phpstan-import-type AnalystRatingsArray from AnalystRatings
 * @phpstan-import-type EsgScoresArray from EsgScores
 * @phpstan-import-type HoldersArray from Holders
 * @phpstan-import-type InsiderTransactionArray from InsiderTransaction
 * @phpstan-import-type OutstandingSharesArray from OutstandingShares
 * @phpstan-import-type EarningsHistoryArray from EarningsHistory
 * @phpstan-import-type EarningsTrendArray from EarningsTrend
 * @phpstan-import-type AnnualEarningsArray from AnnualEarnings
 * @phpstan-import-type FinancialStatementsArray from FinancialStatements
 * @phpstan-type FundamentalsArray array{symbol: non-empty-string, general: CompanyGeneralArray, highlights: HighlightsArray|null, valuation: ValuationArray|null, sharesStats: SharesStatsArray|null, technicals: TechnicalsArray|null, splitsDividends: SplitsDividendsArray|null, analystRatings: AnalystRatingsArray|null, esgScores: EsgScoresArray|null, holders: HoldersArray|null, insiderTransactions: list<InsiderTransactionArray>, annualOutstandingShares: list<OutstandingSharesArray>, quarterlyOutstandingShares: list<OutstandingSharesArray>, earningsHistory: list<EarningsHistoryArray>, earningsTrends: list<EarningsTrendArray>, annualEarnings: list<AnnualEarningsArray>, financialStatements: FinancialStatementsArray}
 */
final readonly class Fundamentals
{

	/**
	 * @param non-empty-string $symbol Symbol the fundamentals were requested for, e.g. `CEZ.PR`
	 * @param list<InsiderTransaction> $insiderTransactions
	 * @param list<OutstandingShares> $annualOutstandingShares
	 * @param list<OutstandingShares> $quarterlyOutstandingShares
	 * @param list<EarningsHistory> $earningsHistory Reported and upcoming quarterly earnings, newest first
	 * @param list<EarningsTrend> $earningsTrends Analyst estimates per period (`0q`, `+1q`, `0y`, `+1y`)
	 * @param list<AnnualEarnings> $annualEarnings
	 */
	public function __construct(
		public string $symbol,
		public CompanyGeneral $general,
		public ?Highlights $highlights = null,
		public ?Valuation $valuation = null,
		public ?SharesStats $sharesStats = null,
		public ?Technicals $technicals = null,
		public ?SplitsDividends $splitsDividends = null,
		public ?AnalystRatings $analystRatings = null,
		public ?EsgScores $esgScores = null,
		public ?Holders $holders = null,
		public array $insiderTransactions = [],
		public array $annualOutstandingShares = [],
		public array $quarterlyOutstandingShares = [],
		public array $earningsHistory = [],
		public array $earningsTrends = [],
		public array $annualEarnings = [],
		public FinancialStatements $financialStatements = new FinancialStatements(),
	)
	{
	}

	/**
	 * @return FundamentalsArray
	 */
	public function toArray(): array
	{
		return [
			'symbol' => $this->symbol,
			'general' => $this->general->toArray(),
			'highlights' => $this->highlights?->toArray(),
			'valuation' => $this->valuation?->toArray(),
			'sharesStats' => $this->sharesStats?->toArray(),
			'technicals' => $this->technicals?->toArray(),
			'splitsDividends' => $this->splitsDividends?->toArray(),
			'analystRatings' => $this->analystRatings?->toArray(),
			'esgScores' => $this->esgScores?->toArray(),
			'holders' => $this->holders?->toArray(),
			'insiderTransactions' => array_map(static fn (InsiderTransaction $item): array => $item->toArray(), $this->insiderTransactions),
			'annualOutstandingShares' => array_map(static fn (OutstandingShares $item): array => $item->toArray(), $this->annualOutstandingShares),
			'quarterlyOutstandingShares' => array_map(static fn (OutstandingShares $item): array => $item->toArray(), $this->quarterlyOutstandingShares),
			'earningsHistory' => array_map(static fn (EarningsHistory $item): array => $item->toArray(), $this->earningsHistory),
			'earningsTrends' => array_map(static fn (EarningsTrend $item): array => $item->toArray(), $this->earningsTrends),
			'annualEarnings' => array_map(static fn (AnnualEarnings $item): array => $item->toArray(), $this->annualEarnings),
			'financialStatements' => $this->financialStatements->toArray(),
		];
	}

}
