<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type RealTimeQuoteArray array{code: non-empty-string, timestamp: int|null, gmtOffset: int, open: float|null, high: float|null, low: float|null, close: float|null, volume: int|null, previousClose: float|null, change: float|null, changePercent: float|null}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class RealTimeQuote
{

	/**
	 * @param non-empty-string $code
	 */
	public function __construct(
		public string $code,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?int $timestamp,
		#[CompilePropertyOptions(name: 'gmtoffset')]
		public int $gmtOffset,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $open,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $high,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $low,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $close,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?int $volume,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $previousClose,
		#[CompilePropertyOptions(nullValues: ['NA'])]
		public ?float $change,
		#[CompilePropertyOptions(name: 'change_p', nullValues: ['NA'])]
		public ?float $changePercent,
	)
	{
	}

	/**
	 * @return RealTimeQuoteArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'timestamp' => $this->timestamp,
			'gmtOffset' => $this->gmtOffset,
			'open' => $this->open,
			'high' => $this->high,
			'low' => $this->low,
			'close' => $this->close,
			'volume' => $this->volume,
			'previousClose' => $this->previousClose,
			'change' => $this->change,
			'changePercent' => $this->changePercent,
		];
	}

}
