<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class PrintTaskFailed extends Exception
{
    public static function missingPrinterId(): self
    {
        return new static('A printer must be specified to print');
    }

    public static function driverFailed(string $message): self
    {
        return new static($message);
    }
}
