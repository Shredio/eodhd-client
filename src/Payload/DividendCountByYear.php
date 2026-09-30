<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type DividendCountByYearArray array{year: int, count: int}
 */
#[CompileObjectMapper(identifier: 'year')]
final readonly class DividendCountByYear
{

	public function __construct(
		#[CompilePropertyOptions(name: 'Year')]
		public int $year,
		#[CompilePropertyOptions(name: 'Count')]
		public int $count,
	)
	{
	}

	/**
	 * @return DividendCountByYearArray
	 */
	public function toArray(): array
	{
		return [
			'year' => $this->year,
			'count' => $this->count,
		];
	}

}
