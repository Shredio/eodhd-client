<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Exception;

interface UnexpectedResponseContentExceptionHandler
{

	public function handle(UnexpectedResponseContentException $exception): void;

}
