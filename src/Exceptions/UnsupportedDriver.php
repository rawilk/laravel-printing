<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class UnsupportedDriver extends Exception
{
    public static function driver(string $driver): self
    {
        return new static("Unsupported print driver: {$driver}");
    }
}
