<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

class DriverConfigNotFound extends PrintingException
{
    public static function forDriver(string $driver): static
    {
        return new static("Driver config not found for print driver [{$driver}].");
    }
}
