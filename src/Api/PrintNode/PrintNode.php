<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

final class PrintNode
{
    public static ?string $apiKey = null;

    /** @var string The base URL for the PrintNode API. */
    public static string $apiBase = 'https://api.printnode.com';

    public static function getApiKey(): ?string
    {
        return self::$apiKey;
    }

    public static function setApiKey(?string $apiKey): void
    {
        self::$apiKey = $apiKey;
    }
}
