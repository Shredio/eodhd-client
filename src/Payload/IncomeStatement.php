<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type IncomeStatementArray array{date: non-empty-string, filingDate: non-empty-string|null, currencySymbol: non-empty-string|null, researchDevelopment: float|null, effectOfAccountingCharges: float|null, incomeBeforeTax: float|null, minorityInterest: float|null, netIncome: float|null, sellingGeneralAdministrative: float|null, sellingAndMarketingExpenses: float|null, grossProfit: float|null, reconciledDepreciation: float|null, ebit: float|null, ebitda: float|null, depreciationAndAmortization: float|null, nonOperatingIncomeNetOther: float|null, operatingIncome: float|null, otherOperatingExpenses: float|null, interestExpense: float|null, taxProvision: float|null, interestIncome: float|null, netInterestIncome: float|null, extraordinaryItems: float|null, nonRecurring: float|null, otherItems: float|null, incomeTaxExpense: float|null, totalRevenue: float|null, totalOperatingExpenses: float|null, costOfRevenue: float|null, totalOtherIncomeExpenseNet: float|null, discontinuedOperations: float|null, netIncomeFromContinuingOps: float|null, netIncomeApplicableToCommonShares: float|null, preferredStockAndOtherAdjustments: float|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class IncomeStatement
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
		public ?float $researchDevelopment,
		public ?float $effectOfAccountingCharges,
		public ?float $incomeBeforeTax,
		public ?float $minorityInterest,
		public ?float $netIncome,
		public ?float $sellingGeneralAdministrative,
		public ?float $sellingAndMarketingExpenses,
		public ?float $grossProfit,
		public ?float $reconciledDepreciation,
		public ?float $ebit,
		public ?float $ebitda,
		public ?float $depreciationAndAmortization,
		public ?float $nonOperatingIncomeNetOther,
		public ?float $operatingIncome,
		public ?float $otherOperatingExpenses,
		public ?float $interestExpense,
		public ?float $taxProvision,
		public ?float $interestIncome,
		public ?float $netInterestIncome,
		public ?float $extraordinaryItems,
		public ?float $nonRecurring,
		public ?float $otherItems,
		public ?float $incomeTaxExpense,
		public ?float $totalRevenue,
		public ?float $totalOperatingExpenses,
		public ?float $costOfRevenue,
		public ?float $totalOtherIncomeExpenseNet,
		public ?float $discontinuedOperations,
		public ?float $netIncomeFromContinuingOps,
		public ?float $netIncomeApplicableToCommonShares,
		public ?float $preferredStockAndOtherAdjustments,
	)
	{
	}

	/**
	 * @return IncomeStatementArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'filingDate' => $this->filingDate,
			'currencySymbol' => $this->currencySymbol,
			'researchDevelopment' => $this->researchDevelopment,
			'effectOfAccountingCharges' => $this->effectOfAccountingCharges,
			'incomeBeforeTax' => $this->incomeBeforeTax,
			'minorityInterest' => $this->minorityInterest,
			'netIncome' => $this->netIncome,
			'sellingGeneralAdministrative' => $this->sellingGeneralAdministrative,
			'sellingAndMarketingExpenses' => $this->sellingAndMarketingExpenses,
			'grossProfit' => $this->grossProfit,
			'reconciledDepreciation' => $this->reconciledDepreciation,
			'ebit' => $this->ebit,
			'ebitda' => $this->ebitda,
			'depreciationAndAmortization' => $this->depreciationAndAmortization,
			'nonOperatingIncomeNetOther' => $this->nonOperatingIncomeNetOther,
			'operatingIncome' => $this->operatingIncome,
			'otherOperatingExpenses' => $this->otherOperatingExpenses,
			'interestExpense' => $this->interestExpense,
			'taxProvision' => $this->taxProvision,
			'interestIncome' => $this->interestIncome,
			'netInterestIncome' => $this->netInterestIncome,
			'extraordinaryItems' => $this->extraordinaryItems,
			'nonRecurring' => $this->nonRecurring,
			'otherItems' => $this->otherItems,
			'incomeTaxExpense' => $this->incomeTaxExpense,
			'totalRevenue' => $this->totalRevenue,
			'totalOperatingExpenses' => $this->totalOperatingExpenses,
			'costOfRevenue' => $this->costOfRevenue,
			'totalOtherIncomeExpenseNet' => $this->totalOtherIncomeExpenseNet,
			'discontinuedOperations' => $this->discontinuedOperations,
			'netIncomeFromContinuingOps' => $this->netIncomeFromContinuingOps,
			'netIncomeApplicableToCommonShares' => $this->netIncomeApplicableToCommonShares,
			'preferredStockAndOtherAdjustments' => $this->preferredStockAndOtherAdjustments,
		];
	}

}
