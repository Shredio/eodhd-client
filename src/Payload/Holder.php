<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type HolderArray array{name: string, date: non-empty-string|null, totalShares: float|null, totalAssets: float|null, currentShares: int|null, change: int|null, changePercent: float|null}
 */
#[CompileObjectMapper(identifier: 'name')]
final readonly class Holder
{

	/**
	 * @param non-empty-string|null $date
	 */
	public function __construct(
		public string $name,
		public ?string $date,
		public ?float $totalShares,
		public ?float $totalAssets,
		#[CompilePropertyOptions(before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $currentShares,
		#[CompilePropertyOptions(before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $change,
		#[CompilePropertyOptions(name: 'change_p')]
		public ?float $changePercent,
	)
	{
	}

	/**
	 * @return HolderArray
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'date' => $this->date,
			'totalShares' => $this->totalShares,
			'totalAssets' => $this->totalAssets,
			'currentShares' => $this->currentShares,
			'change' => $this->change,
			'changePercent' => $this->changePercent,
		];
	}

}
