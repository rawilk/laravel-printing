<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

use Exception;

class InvalidSource extends Exception
{
    public static function fileNotFound(string $filePath): self
    {
        return new static("File not found: {$filePath}");
    }

    public static function invalidUrl(string $url): self
    {
        return new static("Invalid source url: {$url}");
    }
}
