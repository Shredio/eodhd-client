<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Exception;

use Throwable;

final class UnexpectedResponseContentException extends \InvalidArgumentException
{

	/**
	 * @param bool $noticesOnly True when the payload was returned and only non-critical problems were found,
	 *                          e.g. keys added by the API that the payload does not know. False when the payload was discarded.
	 */
	public function __construct(
		string $message,
		?Throwable $previous,
		public readonly string $url,
		public readonly bool $noticesOnly = false,
	)
	{
		parent::__construct($message, previous: $previous);
	}

}
