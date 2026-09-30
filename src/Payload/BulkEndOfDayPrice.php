<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type BulkEndOfDayPriceArray array{code: non-empty-string, exchangeShortName: non-empty-string, date: non-empty-string, open: float, high: float, low: float, close: float, adjustedClose: float, volume: int|float}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class BulkEndOfDayPrice
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string $exchangeShortName
	 * @param non-empty-string $date
	 */
	public function __construct(
		public string $code,
		#[CompilePropertyOptions(name: 'exchange_short_name')]
		public string $exchangeShortName,
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
	 * @return BulkEndOfDayPriceArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'exchangeShortName' => $this->exchangeShortName,
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
