<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type SharesStatsArray array{sharesOutstanding: int|null, sharesFloat: int|null, percentInsiders: float|null, percentInstitutions: float|null, sharesShort: int|null, sharesShortPriorMonth: int|null, shortRatio: float|null, shortPercentOutstanding: float|null, shortPercentFloat: float|null}
 */
#[CompileObjectMapper]
final readonly class SharesStats
{

	public function __construct(
		#[CompilePropertyOptions(name: 'SharesOutstanding', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesOutstanding,
		#[CompilePropertyOptions(name: 'SharesFloat', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesFloat,
		#[CompilePropertyOptions(name: 'PercentInsiders')]
		public ?float $percentInsiders,
		#[CompilePropertyOptions(name: 'PercentInstitutions')]
		public ?float $percentInstitutions,
		#[CompilePropertyOptions(name: 'SharesShort', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesShort,
		#[CompilePropertyOptions(name: 'SharesShortPriorMonth', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $sharesShortPriorMonth,
		#[CompilePropertyOptions(name: 'ShortRatio')]
		public ?float $shortRatio,
		#[CompilePropertyOptions(name: 'ShortPercentOutstanding')]
		public ?float $shortPercentOutstanding,
		#[CompilePropertyOptions(name: 'ShortPercentFloat')]
		public ?float $shortPercentFloat,
	)
	{
	}

	/**
	 * @return SharesStatsArray
	 */
	public function toArray(): array
	{
		return [
			'sharesOutstanding' => $this->sharesOutstanding,
			'sharesFloat' => $this->sharesFloat,
			'percentInsiders' => $this->percentInsiders,
			'percentInstitutions' => $this->percentInstitutions,
			'sharesShort' => $this->sharesShort,
			'sharesShortPriorMonth' => $this->sharesShortPriorMonth,
			'shortRatio' => $this->shortRatio,
			'shortPercentOutstanding' => $this->shortPercentOutstanding,
			'shortPercentFloat' => $this->shortPercentFloat,
		];
	}

}
