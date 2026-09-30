<?php declare(strict_types = 1);

namespace Tests\Mock;

use Shredio\EodhdClient\Exception\UnexpectedResponseContentException;
use Shredio\EodhdClient\Exception\UnexpectedResponseContentExceptionHandler;

final class TestUnexpectedResponseContentExceptionHandler implements UnexpectedResponseContentExceptionHandler
{

	/** @var list<string> */
	public array $messages = [];

	/** @var list<UnexpectedResponseContentException> */
	public array $exceptions = [];

	public function handle(UnexpectedResponseContentException $exception): void
	{
		$this->messages[] = $exception->getMessage();
		$this->exceptions[] = $exception;
	}

}
