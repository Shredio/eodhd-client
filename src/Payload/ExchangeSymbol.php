<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type ExchangeSymbolArray array{code: non-empty-string, name: string, country: non-empty-string|null, exchange: non-empty-string, currency: non-empty-string|null, type: non-empty-string|null, isin: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class ExchangeSymbol
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string|null $country
	 * @param non-empty-string $exchange
	 * @param non-empty-string|null $currency
	 * @param non-empty-string|null $type
	 * @param non-empty-string|null $isin
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Code')]
		public string $code,
		#[CompilePropertyOptions(name: 'Name')]
		public string $name,
		#[CompilePropertyOptions(name: 'Country')]
		public ?string $country,
		#[CompilePropertyOptions(name: 'Exchange')]
		public string $exchange,
		#[CompilePropertyOptions(name: 'Currency')]
		public ?string $currency,
		#[CompilePropertyOptions(name: 'Type')]
		public ?string $type,
		#[CompilePropertyOptions(name: 'Isin')]
		public ?string $isin,
	)
	{
	}

	/**
	 * @return ExchangeSymbolArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'name' => $this->name,
			'country' => $this->country,
			'exchange' => $this->exchange,
			'currency' => $this->currency,
			'type' => $this->type,
			'isin' => $this->isin,
		];
	}

}
