<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type SplitArray array{date: non-empty-string, split: non-empty-string}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class Split
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string $split
	 */
	public function __construct(
		public string $date,
		public string $split,
	)
	{
	}

	/**
	 * @return SplitArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'split' => $this->split,
		];
	}

}
