<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type TechnicalsArray array{beta: float|null, fiftyTwoWeekHigh: float|null, fiftyTwoWeekLow: float|null, fiftyDayMovingAverage: float|null, twoHundredDayMovingAverage: float|null, sharesShort: int|null, sharesShortPriorMonth: int|null, shortRatio: float|null, shortPercent: float|null}
 */
#[CompileObjectMapper]
final readonly class Technicals
{

	public function __construct(
		#[CompilePropertyOptions(name: 'Beta')]
		public ?float $beta,
		#[CompilePropertyOptions(name: '52WeekHigh')]
		public ?float $fiftyTwoWeekHigh,
		#[CompilePropertyOptions(name: '52WeekLow')]
		public ?float $fiftyTwoWeekLow,
		#[CompilePropertyOptions(name: '50DayMA')]
		public ?float $fiftyDayMovingAverage,
		#[CompilePropertyOptions(name: '200DayMA')]
		public ?float $twoHundredDayMovingAverage,
		#[CompilePropertyOptions(name: 'SharesShort', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesShort,
		#[CompilePropertyOptions(name: 'SharesShortPriorMonth', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesShortPriorMonth,
		#[CompilePropertyOptions(name: 'ShortRatio')]
		public ?float $shortRatio,
		#[CompilePropertyOptions(name: 'ShortPercent')]
		public ?float $shortPercent,
	)
	{
	}

	/**
	 * @return TechnicalsArray
	 */
	public function toArray(): array
	{
		return [
			'beta' => $this->beta,
			'fiftyTwoWeekHigh' => $this->fiftyTwoWeekHigh,
			'fiftyTwoWeekLow' => $this->fiftyTwoWeekLow,
			'fiftyDayMovingAverage' => $this->fiftyDayMovingAverage,
			'twoHundredDayMovingAverage' => $this->twoHundredDayMovingAverage,
			'sharesShort' => $this->sharesShort,
			'sharesShortPriorMonth' => $this->sharesShortPriorMonth,
			'shortRatio' => $this->shortRatio,
			'shortPercent' => $this->shortPercent,
		];
	}

}
