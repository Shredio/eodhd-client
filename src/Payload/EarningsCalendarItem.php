<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type EarningsCalendarItemArray array{code: non-empty-string, reportDate: non-empty-string, date: non-empty-string, beforeAfterMarket: non-empty-string|null, currency: non-empty-string|null, actual: float|null, estimate: float|null, difference: float|null, percent: float|null}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class EarningsCalendarItem
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string $reportDate
	 * @param non-empty-string $date
	 * @param non-empty-string|null $beforeAfterMarket
	 * @param non-empty-string|null $currency
	 */
	public function __construct(
		public string $code,
		#[CompilePropertyOptions(name: 'report_date')]
		public string $reportDate,
		public string $date,
		#[CompilePropertyOptions(name: 'before_after_market')]
		public ?string $beforeAfterMarket,
		public ?string $currency,
		public ?float $actual,
		public ?float $estimate,
		public ?float $difference,
		public ?float $percent,
	)
	{
	}

	/**
	 * @return EarningsCalendarItemArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'reportDate' => $this->reportDate,
			'date' => $this->date,
			'beforeAfterMarket' => $this->beforeAfterMarket,
			'currency' => $this->currency,
			'actual' => $this->actual,
			'estimate' => $this->estimate,
			'difference' => $this->difference,
			'percent' => $this->percent,
		];
	}

}
