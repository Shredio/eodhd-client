<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type EsgActivityInvolvementArray array{activity: non-empty-string, involvement: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'activity')]
final readonly class EsgActivityInvolvement
{

	/**
	 * @param non-empty-string $activity
	 * @param non-empty-string|null $involvement
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Activity')]
		public string $activity,
		#[CompilePropertyOptions(name: 'Involvement')]
		public ?string $involvement,
	)
	{
	}

	/**
	 * @return EsgActivityInvolvementArray
	 */
	public function toArray(): array
	{
		return [
			'activity' => $this->activity,
			'involvement' => $this->involvement,
		];
	}

}
