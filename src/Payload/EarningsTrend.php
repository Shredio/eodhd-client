<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type EarningsTrendArray array{date: non-empty-string, period: non-empty-string, growth: float|null, earningsEstimateAvg: float|null, earningsEstimateLow: float|null, earningsEstimateHigh: float|null, earningsEstimateYearAgoEps: float|null, earningsEstimateNumberOfAnalysts: float|null, earningsEstimateGrowth: float|null, revenueEstimateAvg: float|null, revenueEstimateLow: float|null, revenueEstimateHigh: float|null, revenueEstimateYearAgoEps: float|null, revenueEstimateNumberOfAnalysts: float|null, revenueEstimateGrowth: float|null, epsTrendCurrent: float|null, epsTrend7daysAgo: float|null, epsTrend30daysAgo: float|null, epsTrend60daysAgo: float|null, epsTrend90daysAgo: float|null, epsRevisionsUpLast7days: float|null, epsRevisionsUpLast30days: float|null, epsRevisionsDownLast30days: float|null, epsRevisionsDownLast7days: float|null, code: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'date')]
final readonly class EarningsTrend
{

	/**
	 * @param non-empty-string $date
	 * @param non-empty-string $period
	 * @param non-empty-string|null $code
	 */
	public function __construct(
		public string $date,
		public string $period,
		public ?float $growth,
		public ?float $earningsEstimateAvg,
		public ?float $earningsEstimateLow,
		public ?float $earningsEstimateHigh,
		public ?float $earningsEstimateYearAgoEps,
		public ?float $earningsEstimateNumberOfAnalysts,
		public ?float $earningsEstimateGrowth,
		public ?float $revenueEstimateAvg,
		public ?float $revenueEstimateLow,
		public ?float $revenueEstimateHigh,
		public ?float $revenueEstimateYearAgoEps,
		public ?float $revenueEstimateNumberOfAnalysts,
		public ?float $revenueEstimateGrowth,
		public ?float $epsTrendCurrent,
		public ?float $epsTrend7daysAgo,
		public ?float $epsTrend30daysAgo,
		public ?float $epsTrend60daysAgo,
		public ?float $epsTrend90daysAgo,
		public ?float $epsRevisionsUpLast7days,
		public ?float $epsRevisionsUpLast30days,
		public ?float $epsRevisionsDownLast30days,
		public ?float $epsRevisionsDownLast7days = null,
		public ?string $code = null,
	)
	{
	}

	/**
	 * @return EarningsTrendArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'period' => $this->period,
			'growth' => $this->growth,
			'earningsEstimateAvg' => $this->earningsEstimateAvg,
			'earningsEstimateLow' => $this->earningsEstimateLow,
			'earningsEstimateHigh' => $this->earningsEstimateHigh,
			'earningsEstimateYearAgoEps' => $this->earningsEstimateYearAgoEps,
			'earningsEstimateNumberOfAnalysts' => $this->earningsEstimateNumberOfAnalysts,
			'earningsEstimateGrowth' => $this->earningsEstimateGrowth,
			'revenueEstimateAvg' => $this->revenueEstimateAvg,
			'revenueEstimateLow' => $this->revenueEstimateLow,
			'revenueEstimateHigh' => $this->revenueEstimateHigh,
			'revenueEstimateYearAgoEps' => $this->revenueEstimateYearAgoEps,
			'revenueEstimateNumberOfAnalysts' => $this->revenueEstimateNumberOfAnalysts,
			'revenueEstimateGrowth' => $this->revenueEstimateGrowth,
			'epsTrendCurrent' => $this->epsTrendCurrent,
			'epsTrend7daysAgo' => $this->epsTrend7daysAgo,
			'epsTrend30daysAgo' => $this->epsTrend30daysAgo,
			'epsTrend60daysAgo' => $this->epsTrend60daysAgo,
			'epsTrend90daysAgo' => $this->epsTrend90daysAgo,
			'epsRevisionsUpLast7days' => $this->epsRevisionsUpLast7days,
			'epsRevisionsUpLast30days' => $this->epsRevisionsUpLast30days,
			'epsRevisionsDownLast30days' => $this->epsRevisionsDownLast30days,
			'epsRevisionsDownLast7days' => $this->epsRevisionsDownLast7days,
			'code' => $this->code,
		];
	}

}
