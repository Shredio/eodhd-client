<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type HighlightsArray array{marketCapitalization: int|null, marketCapitalizationMln: float|null, ebitda: int|null, peRatio: float|null, pegRatio: float|null, wallStreetTargetPrice: float|null, bookValue: float|null, dividendShare: float|null, dividendYield: float|null, earningsShare: float|null, epsEstimateCurrentYear: float|null, epsEstimateNextYear: float|null, epsEstimateNextQuarter: float|null, epsEstimateCurrentQuarter: float|null, mostRecentQuarter: non-empty-string|null, profitMargin: float|null, operatingMarginTtm: float|null, returnOnAssetsTtm: float|null, returnOnEquityTtm: float|null, revenueTtm: int|null, revenuePerShareTtm: float|null, quarterlyRevenueGrowthYoy: float|null, grossProfitTtm: int|null, dilutedEpsTtm: float|null, quarterlyEarningsGrowthYoy: float|null}
 */
#[CompileObjectMapper]
final readonly class Highlights
{

	/**
	 * @param non-empty-string|null $mostRecentQuarter
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'MarketCapitalization', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $marketCapitalization,
		#[CompilePropertyOptions(name: 'MarketCapitalizationMln')]
		public ?float $marketCapitalizationMln,
		#[CompilePropertyOptions(name: 'EBITDA', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $ebitda,
		#[CompilePropertyOptions(name: 'PERatio')]
		public ?float $peRatio,
		#[CompilePropertyOptions(name: 'PEGRatio')]
		public ?float $pegRatio,
		#[CompilePropertyOptions(name: 'WallStreetTargetPrice')]
		public ?float $wallStreetTargetPrice,
		#[CompilePropertyOptions(name: 'BookValue')]
		public ?float $bookValue,
		#[CompilePropertyOptions(name: 'DividendShare')]
		public ?float $dividendShare,
		#[CompilePropertyOptions(name: 'DividendYield')]
		public ?float $dividendYield,
		#[CompilePropertyOptions(name: 'EarningsShare')]
		public ?float $earningsShare,
		#[CompilePropertyOptions(name: 'EPSEstimateCurrentYear')]
		public ?float $epsEstimateCurrentYear,
		#[CompilePropertyOptions(name: 'EPSEstimateNextYear')]
		public ?float $epsEstimateNextYear,
		#[CompilePropertyOptions(name: 'EPSEstimateNextQuarter')]
		public ?float $epsEstimateNextQuarter,
		#[CompilePropertyOptions(name: 'EPSEstimateCurrentQuarter')]
		public ?float $epsEstimateCurrentQuarter,
		#[CompilePropertyOptions(name: 'MostRecentQuarter')]
		public ?string $mostRecentQuarter,
		#[CompilePropertyOptions(name: 'ProfitMargin')]
		public ?float $profitMargin,
		#[CompilePropertyOptions(name: 'OperatingMarginTTM')]
		public ?float $operatingMarginTtm,
		#[CompilePropertyOptions(name: 'ReturnOnAssetsTTM')]
		public ?float $returnOnAssetsTtm,
		#[CompilePropertyOptions(name: 'ReturnOnEquityTTM')]
		public ?float $returnOnEquityTtm,
		#[CompilePropertyOptions(name: 'RevenueTTM', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $revenueTtm,
		#[CompilePropertyOptions(name: 'RevenuePerShareTTM')]
		public ?float $revenuePerShareTtm,
		#[CompilePropertyOptions(name: 'QuarterlyRevenueGrowthYOY')]
		public ?float $quarterlyRevenueGrowthYoy,
		#[CompilePropertyOptions(name: 'GrossProfitTTM', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $grossProfitTtm,
		#[CompilePropertyOptions(name: 'DilutedEpsTTM')]
		public ?float $dilutedEpsTtm,
		#[CompilePropertyOptions(name: 'QuarterlyEarningsGrowthYOY')]
		public ?float $quarterlyEarningsGrowthYoy,
	)
	{
	}

	/**
	 * @return HighlightsArray
	 */
	public function toArray(): array
	{
		return [
			'marketCapitalization' => $this->marketCapitalization,
			'marketCapitalizationMln' => $this->marketCapitalizationMln,
			'ebitda' => $this->ebitda,
			'peRatio' => $this->peRatio,
			'pegRatio' => $this->pegRatio,
			'wallStreetTargetPrice' => $this->wallStreetTargetPrice,
			'bookValue' => $this->bookValue,
			'dividendShare' => $this->dividendShare,
			'dividendYield' => $this->dividendYield,
			'earningsShare' => $this->earningsShare,
			'epsEstimateCurrentYear' => $this->epsEstimateCurrentYear,
			'epsEstimateNextYear' => $this->epsEstimateNextYear,
			'epsEstimateNextQuarter' => $this->epsEstimateNextQuarter,
			'epsEstimateCurrentQuarter' => $this->epsEstimateCurrentQuarter,
			'mostRecentQuarter' => $this->mostRecentQuarter,
			'profitMargin' => $this->profitMargin,
			'operatingMarginTtm' => $this->operatingMarginTtm,
			'returnOnAssetsTtm' => $this->returnOnAssetsTtm,
			'returnOnEquityTtm' => $this->returnOnEquityTtm,
			'revenueTtm' => $this->revenueTtm,
			'revenuePerShareTtm' => $this->revenuePerShareTtm,
			'quarterlyRevenueGrowthYoy' => $this->quarterlyRevenueGrowthYoy,
			'grossProfitTtm' => $this->grossProfitTtm,
			'dilutedEpsTtm' => $this->dilutedEpsTtm,
			'quarterlyEarningsGrowthYoy' => $this->quarterlyEarningsGrowthYoy,
		];
	}

}
