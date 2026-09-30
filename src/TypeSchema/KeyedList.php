<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * EODHD serializes the lists inside a fundamentals response as JSON objects keyed by position ("0", "1", ...),
 * and an empty one as `[]` or `null`. The values are re-indexed, so the property can be a plain list.
 */
final class KeyedList
{

	public static function values(mixed $value, TypeContext $context): mixed
	{
		if ($value === null) {
			return [];
		}

		if (is_array($value)) {
			return array_values($value);
		}

		return $value;
	}

}
