<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\KeyedList;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-import-type EsgActivityInvolvementArray from EsgActivityInvolvement
 * @phpstan-type EsgScoresArray array{disclaimer: non-empty-string|null, ratingDate: non-empty-string|null, totalEsg: float|null, totalEsgPercentile: float|null, environmentScore: float|null, environmentScorePercentile: float|null, socialScore: float|null, socialScorePercentile: float|null, governanceScore: float|null, governanceScorePercentile: float|null, controversyLevel: int|null, activitiesInvolvement: list<EsgActivityInvolvementArray>}
 */
#[CompileObjectMapper]
final readonly class EsgScores
{

	/**
	 * @param non-empty-string|null $disclaimer
	 * @param non-empty-string|null $ratingDate
	 * @param list<EsgActivityInvolvement> $activitiesInvolvement
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Disclaimer')]
		public ?string $disclaimer,
		#[CompilePropertyOptions(name: 'RatingDate')]
		public ?string $ratingDate,
		#[CompilePropertyOptions(name: 'TotalEsg')]
		public ?float $totalEsg,
		#[CompilePropertyOptions(name: 'TotalEsgPercentile')]
		public ?float $totalEsgPercentile,
		#[CompilePropertyOptions(name: 'EnvironmentScore')]
		public ?float $environmentScore,
		#[CompilePropertyOptions(name: 'EnvironmentScorePercentile')]
		public ?float $environmentScorePercentile,
		#[CompilePropertyOptions(name: 'SocialScore')]
		public ?float $socialScore,
		#[CompilePropertyOptions(name: 'SocialScorePercentile')]
		public ?float $socialScorePercentile,
		#[CompilePropertyOptions(name: 'GovernanceScore')]
		public ?float $governanceScore,
		#[CompilePropertyOptions(name: 'GovernanceScorePercentile')]
		public ?float $governanceScorePercentile,
		#[CompilePropertyOptions(name: 'ControversyLevel')]
		public ?int $controversyLevel,
		#[CompilePropertyOptions(name: 'ActivitiesInvolvement', before: [KeyedList::class, 'values'])]
		public array $activitiesInvolvement,
	)
	{
	}

	/**
	 * @return EsgScoresArray
	 */
	public function toArray(): array
	{
		return [
			'disclaimer' => $this->disclaimer,
			'ratingDate' => $this->ratingDate,
			'totalEsg' => $this->totalEsg,
			'totalEsgPercentile' => $this->totalEsgPercentile,
			'environmentScore' => $this->environmentScore,
			'environmentScorePercentile' => $this->environmentScorePercentile,
			'socialScore' => $this->socialScore,
			'socialScorePercentile' => $this->socialScorePercentile,
			'governanceScore' => $this->governanceScore,
			'governanceScorePercentile' => $this->governanceScorePercentile,
			'controversyLevel' => $this->controversyLevel,
			'activitiesInvolvement' => array_map(static fn (EsgActivityInvolvement $item): array => $item->toArray(), $this->activitiesInvolvement),
		];
	}

}
