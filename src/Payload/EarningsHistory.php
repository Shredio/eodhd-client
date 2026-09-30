<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type EarningsHistoryArray array{reportDate: non-empty-string|null, date: non-empty-string, beforeAfterMarket: non-empty-string|null, currency: non-empty-string|null, epsActual: float|null, epsEstimate: float|null, epsDifference: float|null, surprisePercent: float|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class EarningsHistory
{

	/**
	 * @param non-empty-string|null $reportDate
	 * @param non-empty-string $date
	 * @param non-empty-string|null $beforeAfterMarket
	 * @param non-empty-string|null $currency
	 */
	public function __construct(
		public ?string $reportDate,
		public string $date,
		public ?string $beforeAfterMarket,
		public ?string $currency,
		public ?float $epsActual,
		public ?float $epsEstimate,
		public ?float $epsDifference,
		public ?float $surprisePercent,
	)
	{
	}

	/**
	 * @return EarningsHistoryArray
	 */
	public function toArray(): array
	{
		return [
			'reportDate' => $this->reportDate,
			'date' => $this->date,
			'beforeAfterMarket' => $this->beforeAfterMarket,
			'currency' => $this->currency,
			'epsActual' => $this->epsActual,
			'epsEstimate' => $this->epsEstimate,
			'epsDifference' => $this->epsDifference,
			'surprisePercent' => $this->surprisePercent,
		];
	}

}
