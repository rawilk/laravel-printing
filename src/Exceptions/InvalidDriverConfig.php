<?php

namespace Rawilk\Printing\Exceptions;

use Exception;

class InvalidDriverConfig extends Exception
{
    public static function invalid(string $message): self
    {
        return new static($message);
    }
}
