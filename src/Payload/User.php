<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;

/**
 * @phpstan-type UserArray array{name: non-empty-string|null, email: non-empty-string|null, subscriptionType: non-empty-string|null, paymentMethod: non-empty-string|null, apiRequests: int, apiRequestsDate: non-empty-string, dailyRateLimit: int, extraLimit: int, inviteToken: non-empty-string|null, inviteTokenClicked: int, subscriptionMode: non-empty-string|null, canManageOrganizations: bool}
 */
#[CompileObjectMapper]
final readonly class User
{

	/**
	 * @param non-empty-string|null $name
	 * @param non-empty-string|null $email
	 * @param non-empty-string|null $subscriptionType
	 * @param non-empty-string|null $paymentMethod
	 * @param non-empty-string $apiRequestsDate
	 * @param non-empty-string|null $inviteToken
	 * @param non-empty-string|null $subscriptionMode
	 */
	public function __construct(
		public ?string $name,
		public ?string $email,
		public ?string $subscriptionType,
		public ?string $paymentMethod,
		public int $apiRequests,
		public string $apiRequestsDate,
		public int $dailyRateLimit,
		public int $extraLimit,
		public ?string $inviteToken,
		public int $inviteTokenClicked,
		public ?string $subscriptionMode,
		public bool $canManageOrganizations,
	)
	{
	}

	/**
	 * @return UserArray
	 */
	public function toArray(): array
	{
		return [
			'name' => $this->name,
			'email' => $this->email,
			'subscriptionType' => $this->subscriptionType,
			'paymentMethod' => $this->paymentMethod,
			'apiRequests' => $this->apiRequests,
			'apiRequestsDate' => $this->apiRequestsDate,
			'dailyRateLimit' => $this->dailyRateLimit,
			'extraLimit' => $this->extraLimit,
			'inviteToken' => $this->inviteToken,
			'inviteTokenClicked' => $this->inviteTokenClicked,
			'subscriptionMode' => $this->subscriptionMode,
			'canManageOrganizations' => $this->canManageOrganizations,
		];
	}

}
