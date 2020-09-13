<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class InvalidOption extends Exception
{
    public static function invalidOption(string $message): self
    {
        return new static($message);
    }
}
