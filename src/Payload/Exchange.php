<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type ExchangeArray array{name: non-empty-string, code: non-empty-string, operatingMic: non-empty-string|null, country: non-empty-string|null, currency: non-empty-string|null, countryIso2: non-empty-string|null, countryIso3: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class Exchange
{

	/**
	 * @param non-empty-string $name
	 * @param non-empty-string $code
	 * @param non-empty-string|null $operatingMic
	 * @param non-empty-string|null $country
	 * @param non-empty-string|null $currency
	 * @param non-empty-string|null $countryIso2
	 * @param non-empty-string|null $countryIso3
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Name')]
		public string $name,
		#[CompilePropertyOptions(name: 'Code')]
		public string $code,
		#[CompilePropertyOptions(name: 'OperatingMIC')]
		public ?string $operatingMic,
		#[CompilePropertyOptions(name: 'Country')]
		public ?string $country,
		#[CompilePropertyOptions(name: 'Currency')]
		public ?string $currency,
		#[CompilePropertyOptions(name: 'CountryISO2')]
		public ?string $countryIso2,
		#[CompilePropertyOptions(name: 'CountryISO3')]
		public ?string $countryIso3,
	)
	{
	}

	/**
	 * @return ExchangeArray
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'code' => $this->code,
			'operatingMic' => $this->operatingMic,
			'country' => $this->country,
			'currency' => $this->currency,
			'countryIso2' => $this->countryIso2,
			'countryIso3' => $this->countryIso3,
		];
	}

}
