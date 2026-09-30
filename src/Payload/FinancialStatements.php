<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

/**
 * Financial statements from a fundamentals response, each list in the order returned by the API (newest first).
 *
 * The statement currencies are the section-level `currency_symbol` values; a single statement may carry its own
 * {@see IncomeStatement::$currencySymbol}, which is null for some rows.
 *
 * @phpstan-import-type IncomeStatementArray from IncomeStatement
 * @phpstan-import-type BalanceSheetStatementArray from BalanceSheetStatement
 * @phpstan-import-type CashFlowStatementArray from CashFlowStatement
 * @phpstan-type FinancialStatementsArray array{incomeStatementCurrency: non-empty-string|null, balanceSheetCurrency: non-empty-string|null, cashFlowCurrency: non-empty-string|null, annualIncomeStatements: list<IncomeStatementArray>, quarterlyIncomeStatements: list<IncomeStatementArray>, annualBalanceSheetStatements: list<BalanceSheetStatementArray>, quarterlyBalanceSheetStatements: list<BalanceSheetStatementArray>, annualCashFlowStatements: list<CashFlowStatementArray>, quarterlyCashFlowStatements: list<CashFlowStatementArray>}
 */
final readonly class FinancialStatements
{

	/**
	 * @param non-empty-string|null $incomeStatementCurrency
	 * @param non-empty-string|null $balanceSheetCurrency
	 * @param non-empty-string|null $cashFlowCurrency
	 * @param list<IncomeStatement> $annualIncomeStatements
	 * @param list<IncomeStatement> $quarterlyIncomeStatements
	 * @param list<BalanceSheetStatement> $annualBalanceSheetStatements
	 * @param list<BalanceSheetStatement> $quarterlyBalanceSheetStatements
	 * @param list<CashFlowStatement> $annualCashFlowStatements
	 * @param list<CashFlowStatement> $quarterlyCashFlowStatements
	 */
	public function __construct(
		public ?string $incomeStatementCurrency = null,
		public ?string $balanceSheetCurrency = null,
		public ?string $cashFlowCurrency = null,
		public array $annualIncomeStatements = [],
		public array $quarterlyIncomeStatements = [],
		public array $annualBalanceSheetStatements = [],
		public array $quarterlyBalanceSheetStatements = [],
		public array $annualCashFlowStatements = [],
		public array $quarterlyCashFlowStatements = [],
	)
	{
	}

	/**
	 * @return FinancialStatementsArray
	 */
	public function toArray(): array
	{
		return [
			'incomeStatementCurrency' => $this->incomeStatementCurrency,
			'balanceSheetCurrency' => $this->balanceSheetCurrency,
			'cashFlowCurrency' => $this->cashFlowCurrency,
			'annualIncomeStatements' => array_map(static fn (IncomeStatement $item): array => $item->toArray(), $this->annualIncomeStatements),
			'quarterlyIncomeStatements' => array_map(static fn (IncomeStatement $item): array => $item->toArray(), $this->quarterlyIncomeStatements),
			'annualBalanceSheetStatements' => array_map(static fn (BalanceSheetStatement $item): array => $item->toArray(), $this->annualBalanceSheetStatements),
			'quarterlyBalanceSheetStatements' => array_map(static fn (BalanceSheetStatement $item): array => $item->toArray(), $this->quarterlyBalanceSheetStatements),
			'annualCashFlowStatements' => array_map(static fn (CashFlowStatement $item): array => $item->toArray(), $this->annualCashFlowStatements),
			'quarterlyCashFlowStatements' => array_map(static fn (CashFlowStatement $item): array => $item->toArray(), $this->quarterlyCashFlowStatements),
		];
	}

}
