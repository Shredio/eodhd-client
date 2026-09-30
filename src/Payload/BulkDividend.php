<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type BulkDividendArray array{code: non-empty-string, exchange: non-empty-string, date: non-empty-string, dividend: float, currency: non-empty-string|null, declarationDate: non-empty-string|null, recordDate: non-empty-string|null, paymentDate: non-empty-string|null, period: non-empty-string|null, unadjustedValue: float}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class BulkDividend
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string $exchange
	 * @param non-empty-string $date
	 * @param non-empty-string|null $currency
	 * @param non-empty-string|null $declarationDate
	 * @param non-empty-string|null $recordDate
	 * @param non-empty-string|null $paymentDate
	 * @param non-empty-string|null $period
	 */
	public function __construct(
		public string $code,
		public string $exchange,
		public string $date,
		public float $dividend,
		public ?string $currency,
		public ?string $declarationDate,
		public ?string $recordDate,
		public ?string $paymentDate,
		public ?string $period,
		public float $unadjustedValue,
	)
	{
	}

	/**
	 * @return BulkDividendArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'exchange' => $this->exchange,
			'date' => $this->date,
			'dividend' => $this->dividend,
			'currency' => $this->currency,
			'declarationDate' => $this->declarationDate,
			'recordDate' => $this->recordDate,
			'paymentDate' => $this->paymentDate,
			'period' => $this->period,
			'unadjustedValue' => $this->unadjustedValue,
		];
	}

}
