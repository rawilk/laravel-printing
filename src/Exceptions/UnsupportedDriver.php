<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

class UnsupportedDriver extends PrintingException
{
    public static function driver(string $driver): static
    {
        return new static("Unsupported print driver: {$driver}");
    }
}
