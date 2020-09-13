<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class InvalidDriverConfig extends Exception
{
    public static function invalid(string $message): self
    {
        return new static($message);
    }
}
