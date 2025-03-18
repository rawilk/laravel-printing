<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum Side: string
{
    case OneSided = 'one-sided';
    case TwoSidedLongEdge = 'two-sided-long-edge';
    case TwoSidedShortEdge = 'two-sided-short-edge';
    case Tumble = 'tumble';
}
