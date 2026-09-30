<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Parser;

use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Symfony\Component\HttpClient\Response\StreamableInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

/**
 * Parser for handling large HTTP responses in streaming fashion. The caller is responsible for checking the status
 * code before the body is parsed.
 */
final readonly class LargeResponseParser
{

	/**
	 * Parse JSON response data in streaming mode
	 *
	 * @param HttpClientInterface $client HTTP client instance
	 * @param ResponseInterface $response HTTP response to parse
	 * @param string $pointer JSON pointer of the iterated collection, e.g. `/earnings`; the whole document when empty
	 * @return iterable<array-key, mixed>
	 */
	public function parseJson(HttpClientInterface $client, ResponseInterface $response, string $pointer = ''): iterable
	{
		$options = [
			'decoder' => new ExtJsonDecoder(true),
			'pointer' => $pointer,
		];

		if ($response instanceof StreamableInterface) {
			$items = Items::fromStream($response->toStream(), $options);
		} else {
			$toChunks = static function (ResponseStreamInterface $stream): iterable {
				foreach ($stream as $chunk) {
					yield $chunk->getContent();
				}
			};

			$items = Items::fromIterable($toChunks($client->stream($response)), $options);
		}

		foreach ($items as $value) {
			yield $value;
		}

		$response->cancel();
	}

}
