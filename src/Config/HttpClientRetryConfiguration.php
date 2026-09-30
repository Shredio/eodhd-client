<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Config;

final readonly class HttpClientRetryConfiguration
{

	public function __construct(
		public int $delayMs = 500,
		public float $multiplier = 1.0,
		public int $maxRetries = 3,
	)
	{
	}

}
