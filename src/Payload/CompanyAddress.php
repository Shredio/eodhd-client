<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type CompanyAddressArray array{country: non-empty-string|null, street: non-empty-string|null, city: non-empty-string|null, state: non-empty-string|null, zip: non-empty-string|null}
 */
#[CompileObjectMapper]
final readonly class CompanyAddress
{

	/**
	 * @param non-empty-string|null $country
	 * @param non-empty-string|null $street
	 * @param non-empty-string|null $city
	 * @param non-empty-string|null $state
	 * @param non-empty-string|null $zip
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Country')]
		public ?string $country,
		#[CompilePropertyOptions(name: 'Street')]
		public ?string $street = null,
		#[CompilePropertyOptions(name: 'City')]
		public ?string $city = null,
		#[CompilePropertyOptions(name: 'State')]
		public ?string $state = null,
		#[CompilePropertyOptions(name: 'ZIP')]
		public ?string $zip = null,
	)
	{
	}

	/**
	 * @return CompanyAddressArray
	 */
	public function toArray(): array
	{
		return [
			'country' => $this->country,
			'street' => $this->street,
			'city' => $this->city,
			'state' => $this->state,
			'zip' => $this->zip,
		];
	}

}
