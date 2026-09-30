<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type BulkSplitArray array{code: non-empty-string, exchange: non-empty-string, date: non-empty-string, split: non-empty-string}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class BulkSplit
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string $exchange
	 * @param non-empty-string $date
	 * @param non-empty-string $split
	 */
	public function __construct(
		public string $code,
		public string $exchange,
		public string $date,
		public string $split,
	)
	{
	}

	/**
	 * @return BulkSplitArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'exchange' => $this->exchange,
			'date' => $this->date,
			'split' => $this->split,
		];
	}

}
