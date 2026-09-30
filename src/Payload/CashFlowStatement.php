<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type CashFlowStatementArray array{date: non-empty-string, filingDate: non-empty-string|null, currencySymbol: non-empty-string|null, investments: float|null, changeToLiabilities: float|null, totalCashflowsFromInvestingActivities: float|null, netBorrowings: float|null, totalCashFromFinancingActivities: float|null, changeToOperatingActivities: float|null, netIncome: float|null, changeInCash: float|null, beginPeriodCashFlow: float|null, endPeriodCashFlow: float|null, totalCashFromOperatingActivities: float|null, issuanceOfCapitalStock: float|null, depreciation: float|null, otherCashflowsFromInvestingActivities: float|null, dividendsPaid: float|null, changeToInventory: float|null, changeToAccountReceivables: float|null, salePurchaseOfStock: float|null, otherCashflowsFromFinancingActivities: float|null, changeToNetIncome: float|null, capitalExpenditures: float|null, changeReceivables: float|null, cashFlowsOtherOperating: float|null, exchangeRateChanges: float|null, cashAndCashEquivalentsChanges: float|null, changeInWorkingCapital: float|null, stockBasedCompensation: float|null, otherNonCashItems: float|null, freeCashFlow: float|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class CashFlowStatement
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string|null $filingDate
	 * @param non-empty-string|null $currencySymbol
	 */
	public function __construct(
		public string $date,
		#[CompilePropertyOptions(name: 'filing_date')]
		public ?string $filingDate,
		#[CompilePropertyOptions(name: 'currency_symbol')]
		public ?string $currencySymbol,
		public ?float $investments,
		public ?float $changeToLiabilities,
		public ?float $totalCashflowsFromInvestingActivities,
		public ?float $netBorrowings,
		public ?float $totalCashFromFinancingActivities,
		public ?float $changeToOperatingActivities,
		public ?float $netIncome,
		public ?float $changeInCash,
		public ?float $beginPeriodCashFlow,
		public ?float $endPeriodCashFlow,
		public ?float $totalCashFromOperatingActivities,
		public ?float $issuanceOfCapitalStock,
		public ?float $depreciation,
		public ?float $otherCashflowsFromInvestingActivities,
		public ?float $dividendsPaid,
		public ?float $changeToInventory,
		public ?float $changeToAccountReceivables,
		public ?float $salePurchaseOfStock,
		public ?float $otherCashflowsFromFinancingActivities,
		#[CompilePropertyOptions(name: 'changeToNetincome')]
		public ?float $changeToNetIncome,
		public ?float $capitalExpenditures,
		public ?float $changeReceivables,
		public ?float $cashFlowsOtherOperating,
		public ?float $exchangeRateChanges,
		public ?float $cashAndCashEquivalentsChanges,
		public ?float $changeInWorkingCapital,
		public ?float $stockBasedCompensation,
		public ?float $otherNonCashItems,
		public ?float $freeCashFlow,
	)
	{
	}

	/**
	 * @return CashFlowStatementArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'filingDate' => $this->filingDate,
			'currencySymbol' => $this->currencySymbol,
			'investments' => $this->investments,
			'changeToLiabilities' => $this->changeToLiabilities,
			'totalCashflowsFromInvestingActivities' => $this->totalCashflowsFromInvestingActivities,
			'netBorrowings' => $this->netBorrowings,
			'totalCashFromFinancingActivities' => $this->totalCashFromFinancingActivities,
			'changeToOperatingActivities' => $this->changeToOperatingActivities,
			'netIncome' => $this->netIncome,
			'changeInCash' => $this->changeInCash,
			'beginPeriodCashFlow' => $this->beginPeriodCashFlow,
			'endPeriodCashFlow' => $this->endPeriodCashFlow,
			'totalCashFromOperatingActivities' => $this->totalCashFromOperatingActivities,
			'issuanceOfCapitalStock' => $this->issuanceOfCapitalStock,
			'depreciation' => $this->depreciation,
			'otherCashflowsFromInvestingActivities' => $this->otherCashflowsFromInvestingActivities,
			'dividendsPaid' => $this->dividendsPaid,
			'changeToInventory' => $this->changeToInventory,
			'changeToAccountReceivables' => $this->changeToAccountReceivables,
			'salePurchaseOfStock' => $this->salePurchaseOfStock,
			'otherCashflowsFromFinancingActivities' => $this->otherCashflowsFromFinancingActivities,
			'changeToNetIncome' => $this->changeToNetIncome,
			'capitalExpenditures' => $this->capitalExpenditures,
			'changeReceivables' => $this->changeReceivables,
			'cashFlowsOtherOperating' => $this->cashFlowsOtherOperating,
			'exchangeRateChanges' => $this->exchangeRateChanges,
			'cashAndCashEquivalentsChanges' => $this->cashAndCashEquivalentsChanges,
			'changeInWorkingCapital' => $this->changeInWorkingCapital,
			'stockBasedCompensation' => $this->stockBasedCompensation,
			'otherNonCashItems' => $this->otherNonCashItems,
			'freeCashFlow' => $this->freeCashFlow,
		];
	}

}
