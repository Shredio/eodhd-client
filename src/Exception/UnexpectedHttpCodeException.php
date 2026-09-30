<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Exception;

use Throwable;

final class UnexpectedHttpCodeException extends \RuntimeException
{

	/**
	 * @param string $url Request URL without the API token
	 */
	public function __construct(
		string $message,
		public readonly int $statusCode,
		public readonly string $url,
		?Throwable $previous = null,
	)
	{
		parent::__construct($message, $statusCode, $previous);
	}

}
