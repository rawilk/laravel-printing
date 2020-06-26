<?php

namespace Rawilk\Printing\Exceptions;

use Exception;

class DriverConfigNotFound extends Exception
{
    public static function forDriver(string $driver): self
    {
        return new static("Driver config not found for print driver [{$driver}].");
    }
}
