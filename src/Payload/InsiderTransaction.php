<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\IntegerAmount;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-type InsiderTransactionArray array{date: non-empty-string|null, ownerCik: non-empty-string|null, ownerName: non-empty-string|null, transactionDate: non-empty-string|null, transactionCode: non-empty-string|null, transactionAmount: int|null, transactionPrice: float|null, transactionAcquiredDisposed: non-empty-string|null, postTransactionAmount: int|null, secLink: non-empty-string|null}
 */
#[CompileObjectMapper(identifier: 'ownerName')]
final readonly class InsiderTransaction
{

	/**
	 * @param non-empty-string|null $date
	 * @param non-empty-string|null $ownerCik
	 * @param non-empty-string|null $ownerName
	 * @param non-empty-string|null $transactionDate
	 * @param non-empty-string|null $transactionCode
	 * @param non-empty-string|null $transactionAcquiredDisposed
	 * @param non-empty-string|null $secLink
	 */
	public function __construct(
		public ?string $date,
		public ?string $ownerCik,
		public ?string $ownerName,
		public ?string $transactionDate,
		public ?string $transactionCode,
		#[CompilePropertyOptions(before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $transactionAmount,
		public ?float $transactionPrice,
		public ?string $transactionAcquiredDisposed,
		#[CompilePropertyOptions(before: [IntegerAmount::class, 'roundToInt'])]
		public ?int $postTransactionAmount,
		public ?string $secLink,
	)
	{
	}

	/**
	 * @return InsiderTransactionArray
	 */
	public function toArray(): array
	{
		return [
			'date' => $this->date,
			'ownerCik' => $this->ownerCik,
			'ownerName' => $this->ownerName,
			'transactionDate' => $this->transactionDate,
			'transactionCode' => $this->transactionCode,
			'transactionAmount' => $this->transactionAmount,
			'transactionPrice' => $this->transactionPrice,
			'transactionAcquiredDisposed' => $this->transactionAcquiredDisposed,
			'postTransactionAmount' => $this->postTransactionAmount,
			'secLink' => $this->secLink,
		];
	}

}
