<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type ValuationArray array{trailingPe: float|null, forwardPe: float|null, priceSalesTtm: float|null, priceBookMrq: float|null, enterpriseValue: int|null, enterpriseValueRevenue: float|null, enterpriseValueEbitda: float|null}
 */
#[CompileObjectMapper]
final readonly class Valuation
{

	public function __construct(
		#[CompilePropertyOptions(name: 'TrailingPE')]
		public ?float $trailingPe,
		#[CompilePropertyOptions(name: 'ForwardPE')]
		public ?float $forwardPe,
		#[CompilePropertyOptions(name: 'PriceSalesTTM')]
		public ?float $priceSalesTtm,
		#[CompilePropertyOptions(name: 'PriceBookMRQ')]
		public ?float $priceBookMrq,
		#[CompilePropertyOptions(name: 'EnterpriseValue', before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $enterpriseValue,
		#[CompilePropertyOptions(name: 'EnterpriseValueRevenue')]
		public ?float $enterpriseValueRevenue,
		#[CompilePropertyOptions(name: 'EnterpriseValueEbitda')]
		public ?float $enterpriseValueEbitda,
	)
	{
	}

	/**
	 * @return ValuationArray
	 */
	public function toArray(): array
	{
		return [
			'trailingPe' => $this->trailingPe,
			'forwardPe' => $this->forwardPe,
			'priceSalesTtm' => $this->priceSalesTtm,
			'priceBookMrq' => $this->priceBookMrq,
			'enterpriseValue' => $this->enterpriseValue,
			'enterpriseValueRevenue' => $this->enterpriseValueRevenue,
			'enterpriseValueEbitda' => $this->enterpriseValueEbitda,
		];
	}

}
