<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type EndOfDayPriceArray array{date: non-empty-string, open: float, high: float, low: float, close: float, adjustedClose: float, volume: int|float}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class EndOfDayPrice
{

	/**
	 * @param non-empty-string $date
	 */
	public function __construct(
		public string $date,
		public float $open,
		public float $high,
		public float $low,
		public float $close,
		#[CompilePropertyOptions(name: 'adjusted_close')]
		public float $adjustedClose,
		public int|float $volume,
	)
	{
	}

	/**
	 * @return EndOfDayPriceArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'open' => $this->open,
			'high' => $this->high,
			'low' => $this->low,
			'close' => $this->close,
			'adjustedClose' => $this->adjustedClose,
			'volume' => $this->volume,
		];
	}

}
