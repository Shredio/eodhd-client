<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type DividendArray array{date: non-empty-string, declarationDate: non-empty-string|null, recordDate: non-empty-string|null, paymentDate: non-empty-string|null, period: non-empty-string|null, value: float, unadjustedValue: float, currency: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class Dividend
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string|null $declarationDate
	 * @param non-empty-string|null $recordDate
	 * @param non-empty-string|null $paymentDate
	 * @param non-empty-string|null $period
	 * @param non-empty-string|null $currency
	 */
	public function __construct(
		public string $date,
		public ?string $declarationDate,
		public ?string $recordDate,
		public ?string $paymentDate,
		public ?string $period,
		public float $value,
		public float $unadjustedValue,
		public ?string $currency,
	)
	{
	}

	/**
	 * @return DividendArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'declarationDate' => $this->declarationDate,
			'recordDate' => $this->recordDate,
			'paymentDate' => $this->paymentDate,
			'period' => $this->period,
			'value' => $this->value,
			'unadjustedValue' => $this->unadjustedValue,
			'currency' => $this->currency,
		];
	}

}
