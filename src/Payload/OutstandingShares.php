<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type OutstandingSharesArray array{date: non-empty-string, dateFormatted: non-empty-string, sharesMln: float|null, shares: int|null}
 */
#[CompileObjectMapper(identifier: 'dateFormatted')]
final readonly class OutstandingShares
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string $dateFormatted
	 */
	public function __construct(
		public string $date,
		public string $dateFormatted,
		public ?float $sharesMln,
		#[CompilePropertyOptions(before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $shares,
	)
	{
	}

	/**
	 * @return OutstandingSharesArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'dateFormatted' => $this->dateFormatted,
			'sharesMln' => $this->sharesMln,
			'shares' => $this->shares,
		];
	}

}
