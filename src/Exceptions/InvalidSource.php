<?php

declare(strict_types=1);

namespace Rawilk\Printing\Exceptions;

class InvalidSource extends PrintingException
{
    public static function fileNotFound(string $filePath): static
    {
        return new static("File not found: {$filePath}");
    }

    public static function invalidUrl(string $url): static
    {
        return new static("Invalid source url: {$url}");
    }

    public static function cannotOpenFile(string $filePath): static
    {
        return new static("Could not open file: {$filePath}");
    }
}
