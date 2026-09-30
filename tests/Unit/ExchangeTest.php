<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\EodhdClient\Payload\Exchange;
use Shredio\EodhdClient\Payload\ExchangeSymbol;
use Symfony\Component\HttpClient\Response\MockResponse;
use Tests\TestCase;

final class ExchangeTest extends TestCase
{

	public function testExchangesList(): void
	{
		$exchanges = iterator_to_array($this->createClient('exchanges-list.json')->exchangesList(), false);

		self::assertCount(70, $exchanges);
		self::assertSame(
			(new Exchange('USA Stocks', 'US', 'XNAS, XNYS, OTCM, XCBO', 'USA', 'USD', 'US', 'USA'))->toArray(),
			$exchanges[0]->toArray(),
		);
	}

	public function testExchangeSymbolList(): void
	{
		$symbols = iterator_to_array($this->createClient('exchange-symbol-list-PR.json')->exchangeSymbolList('PR'), false);

		self::assertCount(46, $symbols);
		self::assertSame(
			(new ExchangeSymbol('BEZVA', 'Bezvavlasy as', 'Czech Republic', 'PR', 'CZK', 'Common Stock', 'CZ0009011920'))->toArray(),
			$symbols[0]->toArray(),
		);
		self::assertNull($symbols[1]->isin);
	}

	public function testDelistedSymbolsQuery(): void
	{
		$requestedUrls = [];
		$client = $this->createClientWithResponses(static function (string $method, string $url) use (&$requestedUrls): MockResponse {
			$requestedUrls[] = $url;

			return new MockResponse('[]');
		});

		iterator_to_array($client->exchangeSymbolList('PR'));
		iterator_to_array($client->exchangeSymbolList('PR', delisted: true));

		self::assertSame('https://eodhd.com/api/exchange-symbol-list/PR?api_token=SECRET&fmt=json', $requestedUrls[0]);
		self::assertSame('https://eodhd.com/api/exchange-symbol-list/PR?delisted=1&api_token=SECRET&fmt=json', $requestedUrls[1]);
	}

}
