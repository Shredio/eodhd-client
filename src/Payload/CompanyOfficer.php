<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type CompanyOfficerArray array{name: string, title: non-empty-string|null, yearBorn: int|null}
 */
#[CompileObjectMapper(identifier: 'name')]
final readonly class CompanyOfficer
{

	/**
	 * @param non-empty-string|null $title
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Name')]
		public string $name,
		#[CompilePropertyOptions(name: 'Title')]
		public ?string $title,
		#[CompilePropertyOptions(name: 'YearBorn', nullValues: ['NA'])]
		public ?int $yearBorn,
	)
	{
	}

	/**
	 * @return CompanyOfficerArray
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'title' => $this->title,
			'yearBorn' => $this->yearBorn,
		];
	}

}
