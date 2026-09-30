<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\KeyedList;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-import-type DividendCountByYearArray from DividendCountByYear
 * @phpstan-type SplitsDividendsArray array{forwardAnnualDividendRate: float|null, forwardAnnualDividendYield: float|null, payoutRatio: float|null, dividendDate: non-empty-string|null, exDividendDate: non-empty-string|null, lastSplitFactor: non-empty-string|null, lastSplitDate: non-empty-string|null, numberDividendsByYear: list<DividendCountByYearArray>}
 */
#[CompileObjectMapper]
final readonly class SplitsDividends
{

	/**
	 * @param non-empty-string|null $dividendDate
	 * @param non-empty-string|null $exDividendDate
	 * @param non-empty-string|null $lastSplitFactor
	 * @param non-empty-string|null $lastSplitDate
	 * @param list<DividendCountByYear> $numberDividendsByYear
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'ForwardAnnualDividendRate')]
		public ?float $forwardAnnualDividendRate,
		#[CompilePropertyOptions(name: 'ForwardAnnualDividendYield')]
		public ?float $forwardAnnualDividendYield,
		#[CompilePropertyOptions(name: 'PayoutRatio')]
		public ?float $payoutRatio,
		#[CompilePropertyOptions(name: 'DividendDate')]
		public ?string $dividendDate,
		#[CompilePropertyOptions(name: 'ExDividendDate')]
		public ?string $exDividendDate,
		#[CompilePropertyOptions(name: 'LastSplitFactor')]
		public ?string $lastSplitFactor,
		#[CompilePropertyOptions(name: 'LastSplitDate')]
		public ?string $lastSplitDate,
		#[CompilePropertyOptions(name: 'NumberDividendsByYear', before: [KeyedList::class, 'values'])]
		public array $numberDividendsByYear,
	)
	{
	}

	/**
	 * @return SplitsDividendsArray
	 */
	public function toArray(): array
	{
		return [
			'forwardAnnualDividendRate' => $this->forwardAnnualDividendRate,
			'forwardAnnualDividendYield' => $this->forwardAnnualDividendYield,
			'payoutRatio' => $this->payoutRatio,
			'dividendDate' => $this->dividendDate,
			'exDividendDate' => $this->exDividendDate,
			'lastSplitFactor' => $this->lastSplitFactor,
			'lastSplitDate' => $this->lastSplitDate,
			'numberDividendsByYear' => array_map(static fn (DividendCountByYear $item): array => $item->toArray(), $this->numberDividendsByYear),
		];
	}

}
