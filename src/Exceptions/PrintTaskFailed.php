<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

class PrintTaskFailed extends PrintingException
{
    public static function missingPrinterId(): static
    {
        return new static('A printer must be specified to print!');
    }

    public static function missingSource(): static
    {
        return new static('A print source must be specified!');
    }

    public static function missingContentType(): static
    {
        return new static('Content type must be specified for this driver!');
    }

    public static function noContent(): static
    {
        return new static('No content was provided for the print job!');
    }

    public static function noJobCreated(): static
    {
        return new static('The print job failed to create.');
    }

    public static function driverFailed(string $message): static
    {
        return new static($message);
    }
}
