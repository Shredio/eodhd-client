<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type AnalystRatingsArray array{rating: float|null, targetPrice: float|null, strongBuy: int|null, buy: int|null, hold: int|null, sell: int|null, strongSell: int|null}
 */
#[CompileObjectMapper]
final readonly class AnalystRatings
{

	public function __construct(
		#[CompilePropertyOptions(name: 'Rating')]
		public ?float $rating,
		#[CompilePropertyOptions(name: 'TargetPrice')]
		public ?float $targetPrice,
		#[CompilePropertyOptions(name: 'StrongBuy')]
		public ?int $strongBuy,
		#[CompilePropertyOptions(name: 'Buy')]
		public ?int $buy,
		#[CompilePropertyOptions(name: 'Hold')]
		public ?int $hold,
		#[CompilePropertyOptions(name: 'Sell')]
		public ?int $sell,
		#[CompilePropertyOptions(name: 'StrongSell')]
		public ?int $strongSell,
	)
	{
	}

	/**
	 * @return AnalystRatingsArray
	 */
	public function toArray(): array
	{
		return [
			'rating' => $this->rating,
			'targetPrice' => $this->targetPrice,
			'strongBuy' => $this->strongBuy,
			'buy' => $this->buy,
			'hold' => $this->hold,
			'sell' => $this->sell,
			'strongSell' => $this->strongSell,
		];
	}

}
