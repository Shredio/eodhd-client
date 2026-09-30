<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\TypeSchema;

use Shredio\TypeSchema\Context\TypeContext;

/**
 * Whole amounts (market capitalization, share counts) usually arrive as JSON integers, but EODHD occasionally
 * returns the same amount as a float with a fractional tail (e.g. share counts derived from per-share figures) or
 * as a numeric string. Such a value is rounded to the integer the property expects instead of rejecting the whole
 * payload. Magnitudes no signed 64-bit integer can hold are left untouched, so they still fail as a mapping error.
 */
final class IntegerAmount
{

	private const float MaxMagnitude = 2.0 ** 63;

	public static function roundToInt(mixed $value, TypeContext $context): mixed
	{
		if (is_string($value) && preg_match('~^-?\d+$~', $value) !== 1) {
			$float = filter_var($value, FILTER_VALIDATE_FLOAT);
			if ($float === false) {
				return $value;
			}

			$value = $float;
		}

		if (is_float($value) && is_finite($value) && abs($value) < self::MaxMagnitude) {
			return (int) round($value);
		}

		return $value;
	}

}
