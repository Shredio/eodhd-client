<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Payload;

use Shredio\EodhdClient\TypeSchema\KeyedList;
use Shredio\TypeSchemaCompiler\Attribute\CompileObjectMapper;
use Shredio\TypeSchemaCompiler\Attribute\CompilePropertyOptions;

/**
 * @phpstan-import-type CompanyAddressArray from CompanyAddress
 * @phpstan-import-type CompanyListingArray from CompanyListing
 * @phpstan-import-type CompanyOfficerArray from CompanyOfficer
 * @phpstan-type CompanyGeneralArray array{code: non-empty-string, type: non-empty-string|null, name: non-empty-string, exchange: non-empty-string, currencyCode: non-empty-string|null, currencyName: non-empty-string|null, currencySymbol: non-empty-string|null, countryName: non-empty-string|null, countryIso: non-empty-string|null, openFigi: non-empty-string|null, isin: non-empty-string|null, lei: non-empty-string|null, primaryTicker: non-empty-string|null, cik: non-empty-string|null, employerIdNumber: non-empty-string|null, fiscalYearEnd: non-empty-string|null, ipoDate: non-empty-string|null, internationalDomestic: non-empty-string|null, sector: non-empty-string|null, industry: non-empty-string|null, gicSector: non-empty-string|null, gicGroup: non-empty-string|null, gicIndustry: non-empty-string|null, gicSubIndustry: non-empty-string|null, description: non-empty-string|null, address: non-empty-string|null, addressData: CompanyAddressArray|null, listings: list<CompanyListingArray>, officers: list<CompanyOfficerArray>, phone: non-empty-string|null, webUrl: non-empty-string|null, logoUrl: non-empty-string|null, fullTimeEmployees: int|null, updatedAt: non-empty-string|null, cusip: non-empty-string|null, homeCategory: non-empty-string|null, isDelisted: bool|null}
 */
#[CompileObjectMapper(identifier: 'code')]
final readonly class CompanyGeneral
{

	/**
	 * @param non-empty-string $code
	 * @param non-empty-string|null $type
	 * @param non-empty-string $name
	 * @param non-empty-string $exchange
	 * @param non-empty-string|null $currencyCode
	 * @param non-empty-string|null $currencyName
	 * @param non-empty-string|null $currencySymbol
	 * @param non-empty-string|null $countryName
	 * @param non-empty-string|null $countryIso
	 * @param non-empty-string|null $openFigi
	 * @param non-empty-string|null $isin
	 * @param non-empty-string|null $lei
	 * @param non-empty-string|null $primaryTicker
	 * @param non-empty-string|null $cik
	 * @param non-empty-string|null $employerIdNumber
	 * @param non-empty-string|null $fiscalYearEnd
	 * @param non-empty-string|null $ipoDate
	 * @param non-empty-string|null $internationalDomestic
	 * @param non-empty-string|null $sector
	 * @param non-empty-string|null $industry
	 * @param non-empty-string|null $gicSector
	 * @param non-empty-string|null $gicGroup
	 * @param non-empty-string|null $gicIndustry
	 * @param non-empty-string|null $gicSubIndustry
	 * @param non-empty-string|null $description
	 * @param non-empty-string|null $address
	 * @param list<CompanyListing> $listings
	 * @param list<CompanyOfficer> $officers
	 * @param non-empty-string|null $phone
	 * @param non-empty-string|null $webUrl
	 * @param non-empty-string|null $logoUrl
	 * @param non-empty-string|null $updatedAt
	 * @param non-empty-string|null $cusip
	 * @param non-empty-string|null $homeCategory
	 */
	public function __construct(
		#[CompilePropertyOptions(name: 'Code')]
		public string $code,
		#[CompilePropertyOptions(name: 'Type')]
		public ?string $type,
		#[CompilePropertyOptions(name: 'Name')]
		public string $name,
		#[CompilePropertyOptions(name: 'Exchange')]
		public string $exchange,
		#[CompilePropertyOptions(name: 'CurrencyCode')]
		public ?string $currencyCode,
		#[CompilePropertyOptions(name: 'CurrencyName')]
		public ?string $currencyName,
		#[CompilePropertyOptions(name: 'CurrencySymbol')]
		public ?string $currencySymbol,
		#[CompilePropertyOptions(name: 'CountryName')]
		public ?string $countryName,
		#[CompilePropertyOptions(name: 'CountryISO')]
		public ?string $countryIso,
		#[CompilePropertyOptions(name: 'OpenFigi')]
		public ?string $openFigi,
		#[CompilePropertyOptions(name: 'ISIN')]
		public ?string $isin,
		#[CompilePropertyOptions(name: 'LEI')]
		public ?string $lei,
		#[CompilePropertyOptions(name: 'PrimaryTicker')]
		public ?string $primaryTicker,
		#[CompilePropertyOptions(name: 'CIK')]
		public ?string $cik,
		#[CompilePropertyOptions(name: 'EmployerIdNumber')]
		public ?string $employerIdNumber,
		#[CompilePropertyOptions(name: 'FiscalYearEnd')]
		public ?string $fiscalYearEnd,
		#[CompilePropertyOptions(name: 'IPODate')]
		public ?string $ipoDate,
		#[CompilePropertyOptions(name: 'InternationalDomestic')]
		public ?string $internationalDomestic,
		#[CompilePropertyOptions(name: 'Sector')]
		public ?string $sector,
		#[CompilePropertyOptions(name: 'Industry')]
		public ?string $industry,
		#[CompilePropertyOptions(name: 'GicSector')]
		public ?string $gicSector,
		#[CompilePropertyOptions(name: 'GicGroup')]
		public ?string $gicGroup,
		#[CompilePropertyOptions(name: 'GicIndustry')]
		public ?string $gicIndustry,
		#[CompilePropertyOptions(name: 'GicSubIndustry')]
		public ?string $gicSubIndustry,
		#[CompilePropertyOptions(name: 'Description')]
		public ?string $description,
		#[CompilePropertyOptions(name: 'Address')]
		public ?string $address,
		#[CompilePropertyOptions(name: 'AddressData')]
		public ?CompanyAddress $addressData,
		#[CompilePropertyOptions(name: 'Listings', before: [KeyedList::class, 'values'])]
		public array $listings,
		#[CompilePropertyOptions(name: 'Officers', before: [KeyedList::class, 'values'])]
		public array $officers,
		#[CompilePropertyOptions(name: 'Phone')]
		public ?string $phone,
		#[CompilePropertyOptions(name: 'WebURL')]
		public ?string $webUrl,
		#[CompilePropertyOptions(name: 'LogoURL')]
		public ?string $logoUrl,
		#[CompilePropertyOptions(name: 'FullTimeEmployees')]
		public ?int $fullTimeEmployees,
		#[CompilePropertyOptions(name: 'UpdatedAt')]
		public ?string $updatedAt,
		#[CompilePropertyOptions(name: 'CUSIP')]
		public ?string $cusip = null,
		#[CompilePropertyOptions(name: 'HomeCategory')]
		public ?string $homeCategory = null,
		#[CompilePropertyOptions(name: 'IsDelisted')]
		public ?bool $isDelisted = null,
	)
	{
	}

	/**
	 * @return CompanyGeneralArray
	 */
	public function toArray(): array
	{
		return [
			'code' => $this->code,
			'type' => $this->type,
			'name' => $this->name,
			'exchange' => $this->exchange,
			'currencyCode' => $this->currencyCode,
			'currencyName' => $this->currencyName,
			'currencySymbol' => $this->currencySymbol,
			'countryName' => $this->countryName,
			'countryIso' => $this->countryIso,
			'openFigi' => $this->openFigi,
			'isin' => $this->isin,
			'lei' => $this->lei,
			'primaryTicker' => $this->primaryTicker,
			'cik' => $this->cik,
			'employerIdNumber' => $this->employerIdNumber,
			'fiscalYearEnd' => $this->fiscalYearEnd,
			'ipoDate' => $this->ipoDate,
			'internationalDomestic' => $this->internationalDomestic,
			'sector' => $this->sector,
			'industry' => $this->industry,
			'gicSector' => $this->gicSector,
			'gicGroup' => $this->gicGroup,
			'gicIndustry' => $this->gicIndustry,
			'gicSubIndustry' => $this->gicSubIndustry,
			'description' => $this->description,
			'address' => $this->address,
			'addressData' => $this->addressData?->toArray(),
			'listings' => array_map(static fn (CompanyListing $item): array => $item->toArray(), $this->listings),
			'officers' => array_map(static fn (CompanyOfficer $item): array => $item->toArray(), $this->officers),
			'phone' => $this->phone,
			'webUrl' => $this->webUrl,
			'logoUrl' => $this->logoUrl,
			'fullTimeEmployees' => $this->fullTimeEmployees,
			'updatedAt' => $this->updatedAt,
			'cusip' => $this->cusip,
			'homeCategory' => $this->homeCategory,
			'isDelisted' => $this->isDelisted,
		];
	}

}
