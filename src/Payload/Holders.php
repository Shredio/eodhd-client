<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\KeyedList;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-import-type HolderArray from Holder
 * @phpstan-type HoldersArray array{institutions: list<HolderArray>, funds: list<HolderArray>}
 */
#[CompileObjectMapper]
final readonly class Holders
{

	/**
	 * @param list<Holder> $institutions
	 * @param list<Holder> $funds
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Institutions', before: [KeyedList::class, 'values'])]
		public array $institutions,
		#[CompilePropertyOptions(name: 'Funds', before: [KeyedList::class, 'values'])]
		public array $funds,
	)
	{
	}

	/**
	 * @return HoldersArray
	 */
	public function toArray(): array
	{
		return [
			'institutions' => array_map(static fn (Holder $item): array => $item->toArray(), $this->institutions),
			'funds' => array_map(static fn (Holder $item): array => $item->toArray(), $this->funds),
		];
	}

}
