<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

class InvalidOption extends PrintingException
{
    public static function invalidOption(string $message): static
    {
        return new static($message);
    }
}
