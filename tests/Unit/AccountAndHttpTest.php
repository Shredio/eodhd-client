<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\EodhdClient\Exception\UnexpectedHttpCodeException;
use Shredio\EodhdClient\Payload\User;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class AccountAndHttpTest extends TestCase
{

	public function testUser(): void
	{
		$user = $this->createClient('user.json')->user();

		self::assertSame(
			(new User(
				name: 'Jane Doe',
				email: 'jane.doe@example.com',
				subscriptionType: 'monthly',
				paymentMethod: 'Card',
				apiRequests: 42,
				apiRequestsDate: '2026-01-01',
				dailyRateLimit: 1000,
				extraLimit: 0,
				inviteToken: null,
				inviteTokenClicked: 0,
				subscriptionMode: 'paid',
				canManageOrganizations: false,
			))->toArray(),
			$user?->toArray(),
		);
	}

	public function testUnexpectedStatusCodeDoesNotLeakApiToken(): void
	{
		$client = $this->createClientWithResponses([new MockResponse('Unauthenticated', ['http_code' => 401])]);

		try {
			$client->fundamentals('CEZ.PR');
			self::fail('An exception was expected');
		} catch (UnexpectedHttpCodeException $exception) {
			self::assertSame(401, $exception->statusCode);
			self::assertSame('https://eodhd.com/api/fundamentals/CEZ.PR', $exception->url);
			self::assertSame(
				'Unexpected HTTP status code 401 received from https://eodhd.com/api/fundamentals/CEZ.PR: Unauthenticated',
				$exception->getMessage(),
			);
			self::assertStringNotContainsString('SECRET', $exception->getMessage());
		}
	}

	public function testNotFoundOfListEndpointThrows(): void
	{
		$client = $this->createClientWithResponses([new MockResponse('Exchange Not Found.', ['http_code' => 404])]);

		$this->expectException(UnexpectedHttpCodeException::class);
		$this->expectExceptionMessage('Exchange Not Found.');

		iterator_to_array($client->exchangeSymbolList('NOPE'));
	}

	public function testRawRequestAddsTokenAndFormat(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(static function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return new MockResponse('{}');
		});

		$client->request('technical/AAPL.US', ['function' => 'sma'])->getContent();

		self::assertSame(['https://eodhd.com/api/technical/AAPL.US?function=sma&api_token=SECRET&fmt=json'], $requestedUrls);
	}

}
