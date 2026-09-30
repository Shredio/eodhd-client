<?php declare(strict_types = 1);

namespace Tests;

use Shredio\EodhdClient\Exception\UnexpectedResponseContentExceptionHandler;
use Shredio\EodhdClient\SymfonyEodhdClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	protected const string FixturesDirectory = __DIR__ . '/Unit/fixtures';

	/**
	 * Strict client answering with the given fixture file (relative to the fixtures directory), followed by `$responses`.
	 *
	 * @param list<MockResponse> $responses
	 */
	protected function createClient(string $fixture, array $responses = []): SymfonyEodhdClient
	{
		return $this->createClientWithResponses([
			MockResponse::fromFile(sprintf('%s/%s', self::FixturesDirectory, $fixture)),
			...$responses,
		]);
	}

	/**
	 * @param list<MockResponse>|callable(string $method, string $url): MockResponse $responses
	 */
	protected function createClientWithResponses(
		array|callable $responses,
		?UnexpectedResponseContentExceptionHandler $handler = null,
		bool $strictMode = true,
	): SymfonyEodhdClient
	{
		return new SymfonyEodhdClient(new MockHttpClient($responses), 'SECRET', $handler, $strictMode, retryConfiguration: null);
	}

	/**
	 * @return array<string, mixed>
	 */
	protected function readFixture(string $fixture): array
	{
		$contents = file_get_contents(sprintf('%s/%s', self::FixturesDirectory, $fixture));
		self::assertIsString($contents);

		$values = json_decode($contents, true, flags: JSON_THROW_ON_ERROR);
		self::assertIsArray($values);

		/** @var array<string, mixed> $values */
		return $values;
	}

}
