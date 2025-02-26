<?php

declare(strict_types=1);

namespace Rawilk\Printing\Enums;

/**
 * Printing drivers supported by the package.
 */
enum PrintDriver: string
{
    case PrintNode = 'printnode';
    case Cups = 'cups';
}
