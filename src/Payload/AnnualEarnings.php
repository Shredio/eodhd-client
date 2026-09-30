<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type AnnualEarningsArray array{date: non-empty-string, epsActual: float|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class AnnualEarnings
{

	/**
	 * @param non-empty-string $date
	 */
	public function __construct(
		public string $date,
		public ?float $epsActual,
	)
	{
	}

	/**
	 * @return AnnualEarningsArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'epsActual' => $this->epsActual,
		];
	}

}
