<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class PrintTaskFailed extends Exception
{
    public static function missingPrinterId(): self
    {
        return new static('A printer must be specified to print!');
    }

    public static function missingSource(): self
    {
        return new static('A print source must be specified!');
    }

    public static function missingContentType(): self
    {
        return new static('Content type must be specified for this driver!');
    }

    public static function noContent(): self
    {
        return new static('No content was provided for the print job!');
    }

    public static function noJobCreated(): self
    {
        return new static('The print job failed to create.');
    }

    public static function driverFailed(string $message): self
    {
        return new static($message);
    }
}
