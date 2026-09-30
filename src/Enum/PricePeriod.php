<?php declare(strict_types = 1);

namespace Shredio\EodhdClient\Enum;

enum PricePeriod: string
{

	case Daily = 'd';
	case Weekly = 'w';
	case Monthly = 'm';

}
