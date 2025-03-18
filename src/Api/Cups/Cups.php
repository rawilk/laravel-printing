<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use SensitiveParameter;

final class Cups
{
    public const DEFAULT_PORT = 631;

    public const DEFAULT_SECURE = false;

    /**
     * The IP of the server the CUPS instance is running on.
     */
    public static ?string $ip = null;

    /**
     * The username to authenticate to the CUPS server with.
     */
    public static ?string $username = null;

    /**
     * The password to authenticate to the CUPS server with.
     */
    public static ?string $password = null;

    /**
     * The port the CUPS server is running on.
     */
    public static int $port = self::DEFAULT_PORT;

    /**
     * Indicates if http requests to the CUPS server should use `https`.
     */
    public static bool $secure = self::DEFAULT_SECURE;

    public static function getIp(): ?string
    {
        return self::$ip;
    }

    public static function getAuth(): array
    {
        return [self::$username, self::$password];
    }

    public static function getPort(): int
    {
        return self::$port;
    }

    public static function getSecure(): bool
    {
        return self::$secure;
    }

    public static function setIp(?string $ip): void
    {
        self::$ip = $ip;
    }

    public static function setAuth(?string $username, #[SensitiveParameter] ?string $password): void
    {
        self::$username = $username;
        self::$password = $password;
    }

    public static function setPort(int $port): void
    {
        self::$port = $port;
    }

    public static function setSecure(bool $secure): void
    {
        self::$secure = $secure;
    }

    /**
     * Reset credentials to default. This is mostly useful for testing.
     */
    public static function reset(): void
    {
        self::$ip = null;
        self::$username = null;
        self::$password = null;
        self::$port = self::DEFAULT_PORT;
        self::$secure = self::DEFAULT_SECURE;
    }
}
