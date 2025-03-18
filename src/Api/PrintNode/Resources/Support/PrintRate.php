<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\Support;

use Rawilk\Printing\Api\PrintNode\PrintNodeObject;

/**
 * A `PrintRate` describes a printer's reported print rate.
 *
 * @property-read string $unit Can be one of: `ppm`, `ipm`, `lmp`, or `cpm`.
 * @property-read float $rate
 */
class PrintRate extends PrintNodeObject
{
}
