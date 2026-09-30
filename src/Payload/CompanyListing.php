<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type CompanyListingArray array{code: non-empty-string, exchange: non-empty-string, name: string}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class CompanyListing
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string $exchange
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Code')]
		public string $code,
		#[CompilePropertyOptions(name: 'Exchange')]
		public string $exchange,
		#[CompilePropertyOptions(name: 'Name')]
		public string $name,
	)
	{
	}

	/**
	 * @return CompanyListingArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'exchange' => $this->exchange,
			'name' => $this->name,
		];
	}

}
