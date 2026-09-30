<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type BalanceSheetStatementArray array{date: non-empty-string, filingDate: non-empty-string|null, currencySymbol: non-empty-string|null, totalAssets: float|null, intangibleAssets: float|null, earningAssets: float|null, otherCurrentAssets: float|null, totalLiab: float|null, totalStockholderEquity: float|null, deferredLongTermLiab: float|null, otherCurrentLiab: float|null, commonStock: float|null, capitalStock: float|null, retainedEarnings: float|null, otherLiab: float|null, goodWill: float|null, otherAssets: float|null, cash: float|null, cashAndEquivalents: float|null, totalCurrentLiabilities: float|null, currentDeferredRevenue: float|null, netDebt: float|null, shortTermDebt: float|null, shortLongTermDebt: float|null, shortLongTermDebtTotal: float|null, otherStockholderEquity: float|null, propertyPlantEquipment: float|null, totalCurrentAssets: float|null, longTermInvestments: float|null, netTangibleAssets: float|null, shortTermInvestments: float|null, netReceivables: float|null, longTermDebt: float|null, inventory: float|null, accountsPayable: float|null, totalPermanentEquity: float|null, noncontrollingInterestInConsolidatedEntity: float|null, temporaryEquityRedeemableNoncontrollingInterests: float|null, accumulatedOtherComprehensiveIncome: float|null, additionalPaidInCapital: float|null, commonStockTotalEquity: float|null, preferredStockTotalEquity: float|null, retainedEarningsTotalEquity: float|null, treasuryStock: float|null, accumulatedAmortization: float|null, nonCurrentAssetsOther: float|null, deferredLongTermAssetCharges: float|null, nonCurrentAssetsTotal: float|null, capitalLeaseObligations: float|null, longTermDebtTotal: float|null, nonCurrentLiabilitiesOther: float|null, nonCurrentLiabilitiesTotal: float|null, negativeGoodwill: float|null, warrants: float|null, preferredStockRedeemable: float|null, capitalSurplus: float|null, liabilitiesAndStockholdersEquity: float|null, cashAndShortTermInvestments: float|null, propertyPlantAndEquipmentGross: float|null, propertyPlantAndEquipmentNet: float|null, accumulatedDepreciation: float|null, netWorkingCapital: float|null, netInvestedCapital: float|null, commonStockSharesOutstanding: float|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class BalanceSheetStatement
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
		public ?float $totalAssets,
		public ?float $intangibleAssets,
		public ?float $earningAssets,
		public ?float $otherCurrentAssets,
		public ?float $totalLiab,
		public ?float $totalStockholderEquity,
		public ?float $deferredLongTermLiab,
		public ?float $otherCurrentLiab,
		public ?float $commonStock,
		public ?float $capitalStock,
		public ?float $retainedEarnings,
		public ?float $otherLiab,
		public ?float $goodWill,
		public ?float $otherAssets,
		public ?float $cash,
		public ?float $cashAndEquivalents,
		public ?float $totalCurrentLiabilities,
		public ?float $currentDeferredRevenue,
		public ?float $netDebt,
		public ?float $shortTermDebt,
		public ?float $shortLongTermDebt,
		public ?float $shortLongTermDebtTotal,
		public ?float $otherStockholderEquity,
		public ?float $propertyPlantEquipment,
		public ?float $totalCurrentAssets,
		public ?float $longTermInvestments,
		public ?float $netTangibleAssets,
		public ?float $shortTermInvestments,
		public ?float $netReceivables,
		public ?float $longTermDebt,
		public ?float $inventory,
		public ?float $accountsPayable,
		public ?float $totalPermanentEquity,
		public ?float $noncontrollingInterestInConsolidatedEntity,
		public ?float $temporaryEquityRedeemableNoncontrollingInterests,
		public ?float $accumulatedOtherComprehensiveIncome,
		public ?float $additionalPaidInCapital,
		public ?float $commonStockTotalEquity,
		public ?float $preferredStockTotalEquity,
		public ?float $retainedEarningsTotalEquity,
		public ?float $treasuryStock,
		public ?float $accumulatedAmortization,
		#[CompilePropertyOptions(name: 'nonCurrrentAssetsOther')]
		public ?float $nonCurrentAssetsOther,
		public ?float $deferredLongTermAssetCharges,
		public ?float $nonCurrentAssetsTotal,
		public ?float $capitalLeaseObligations,
		public ?float $longTermDebtTotal,
		public ?float $nonCurrentLiabilitiesOther,
		public ?float $nonCurrentLiabilitiesTotal,
		public ?float $negativeGoodwill,
		public ?float $warrants,
		public ?float $preferredStockRedeemable,
		#[CompilePropertyOptions(name: 'capitalSurpluse')]
		public ?float $capitalSurplus,
		public ?float $liabilitiesAndStockholdersEquity,
		public ?float $cashAndShortTermInvestments,
		public ?float $propertyPlantAndEquipmentGross,
		public ?float $propertyPlantAndEquipmentNet,
		public ?float $accumulatedDepreciation,
		public ?float $netWorkingCapital,
		public ?float $netInvestedCapital,
		public ?float $commonStockSharesOutstanding,
	)
	{
	}

	/**
	 * @return BalanceSheetStatementArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'filingDate' => $this->filingDate,
			'currencySymbol' => $this->currencySymbol,
			'totalAssets' => $this->totalAssets,
			'intangibleAssets' => $this->intangibleAssets,
			'earningAssets' => $this->earningAssets,
			'otherCurrentAssets' => $this->otherCurrentAssets,
			'totalLiab' => $this->totalLiab,
			'totalStockholderEquity' => $this->totalStockholderEquity,
			'deferredLongTermLiab' => $this->deferredLongTermLiab,
			'otherCurrentLiab' => $this->otherCurrentLiab,
			'commonStock' => $this->commonStock,
			'capitalStock' => $this->capitalStock,
			'retainedEarnings' => $this->retainedEarnings,
			'otherLiab' => $this->otherLiab,
			'goodWill' => $this->goodWill,
			'otherAssets' => $this->otherAssets,
			'cash' => $this->cash,
			'cashAndEquivalents' => $this->cashAndEquivalents,
			'totalCurrentLiabilities' => $this->totalCurrentLiabilities,
			'currentDeferredRevenue' => $this->currentDeferredRevenue,
			'netDebt' => $this->netDebt,
			'shortTermDebt' => $this->shortTermDebt,
			'shortLongTermDebt' => $this->shortLongTermDebt,
			'shortLongTermDebtTotal' => $this->shortLongTermDebtTotal,
			'otherStockholderEquity' => $this->otherStockholderEquity,
			'propertyPlantEquipment' => $this->propertyPlantEquipment,
			'totalCurrentAssets' => $this->totalCurrentAssets,
			'longTermInvestments' => $this->longTermInvestments,
			'netTangibleAssets' => $this->netTangibleAssets,
			'shortTermInvestments' => $this->shortTermInvestments,
			'netReceivables' => $this->netReceivables,
			'longTermDebt' => $this->longTermDebt,
			'inventory' => $this->inventory,
			'accountsPayable' => $this->accountsPayable,
			'totalPermanentEquity' => $this->totalPermanentEquity,
			'noncontrollingInterestInConsolidatedEntity' => $this->noncontrollingInterestInConsolidatedEntity,
			'temporaryEquityRedeemableNoncontrollingInterests' => $this->temporaryEquityRedeemableNoncontrollingInterests,
			'accumulatedOtherComprehensiveIncome' => $this->accumulatedOtherComprehensiveIncome,
			'additionalPaidInCapital' => $this->additionalPaidInCapital,
			'commonStockTotalEquity' => $this->commonStockTotalEquity,
			'preferredStockTotalEquity' => $this->preferredStockTotalEquity,
			'retainedEarningsTotalEquity' => $this->retainedEarningsTotalEquity,
			'treasuryStock' => $this->treasuryStock,
			'accumulatedAmortization' => $this->accumulatedAmortization,
			'nonCurrentAssetsOther' => $this->nonCurrentAssetsOther,
			'deferredLongTermAssetCharges' => $this->deferredLongTermAssetCharges,
			'nonCurrentAssetsTotal' => $this->nonCurrentAssetsTotal,
			'capitalLeaseObligations' => $this->capitalLeaseObligations,
			'longTermDebtTotal' => $this->longTermDebtTotal,
			'nonCurrentLiabilitiesOther' => $this->nonCurrentLiabilitiesOther,
			'nonCurrentLiabilitiesTotal' => $this->nonCurrentLiabilitiesTotal,
			'negativeGoodwill' => $this->negativeGoodwill,
			'warrants' => $this->warrants,
			'preferredStockRedeemable' => $this->preferredStockRedeemable,
			'capitalSurplus' => $this->capitalSurplus,
			'liabilitiesAndStockholdersEquity' => $this->liabilitiesAndStockholdersEquity,
			'cashAndShortTermInvestments' => $this->cashAndShortTermInvestments,
			'propertyPlantAndEquipmentGross' => $this->propertyPlantAndEquipmentGross,
			'propertyPlantAndEquipmentNet' => $this->propertyPlantAndEquipmentNet,
			'accumulatedDepreciation' => $this->accumulatedDepreciation,
			'netWorkingCapital' => $this->netWorkingCapital,
			'netInvestedCapital' => $this->netInvestedCapital,
			'commonStockSharesOutstanding' => $this->commonStockSharesOutstanding,
		];
	}

}
