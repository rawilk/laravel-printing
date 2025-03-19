<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use Rawilk\Printing\Api\Cups\Exceptions\InvalidRequest;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use SensitiveParameter;

class BaseCupsClient implements CupsClientInterface
{
    use Conditionable;
    use Macroable;

    private const DEFAULT_CONFIG = [
        'ip' => null,
        'username' => null,
        'password' => null,
        'port' => Cups::DEFAULT_PORT,
        'secure' => Cups::DEFAULT_SECURE,
    ];

    private array $config;

    private RequestOptions $defaultOpts;

    public function __construct(#[SensitiveParameter] ?array $config = [])
    {
        $config = array_merge(self::DEFAULT_CONFIG, $config ?? []);
        $this->guardAgainstInvalidConfig($config);

        $this->config = $config;

        $this->setDefaultOpts();
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getIp(): ?string
    {
        return $this->config['ip'];
    }

    public function getAuth(): array
    {
        return [
            $this->config['username'],
            $this->config['password'],
        ];
    }

    public function getPort(): ?int
    {
        return $this->config['port'];
    }

    public function getSecure(): ?bool
    {
        return $this->config['secure'];
    }

    public function request(string|PendingRequest $binary, array|RequestOptions $opts = []): CupsResponse
    {
        $defaultRequestOpts = $this->defaultOpts;

        $opts = $defaultRequestOpts->merge($opts, true);

        [$username, $password] = $this->authForRequest($opts);

        $requestor = new CupsRequestor(
            ip: $this->ipForRequest($opts),
            username: $username,
            password: $password,
            port: $this->portForRequest($opts),
            secure: $this->secureForRequest($opts),
        );

        return $requestor->request(
            binary: $binary,
            opts: $opts,
        );
    }

    private function ipForRequest(RequestOptions $opts): string
    {
        $ip = $opts->ip ?? $this->getIp() ?? Cups::getIp();

        throw_if(
            blank($ip),
            InvalidRequest::class,
            <<<'TXT'
            No CUPS Server IP address provided. Set your IP when constructing the
            CupsClient instance, or provide it on a per-request basis using the
            `ip` key in the $opts argument.
            TXT
        );

        return $ip;
    }

    private function authForRequest(RequestOptions $opts): array
    {
        [$thisUsername, $thisPassword] = $this->getAuth();
        [$globalUsername, $globalPassword] = Cups::getAuth();

        $username = $opts->username ?? $thisUsername ?? $globalUsername;
        $password = $opts->password ?? $thisPassword ?? $globalPassword;

        return [$username, $password];
    }

    private function portForRequest(RequestOptions $opts): int
    {
        $port = $opts->port ?? $this->getPort() ?? Cups::getPort();

        throw_if(
            $port < 1,
            InvalidRequest::class,
            'Invalid server port: ' . $port,
        );

        return $port;
    }

    private function secureForRequest(RequestOptions $opts): bool
    {
        return $opts->secure ?? $this->getSecure() ?? Cups::getSecure();
    }

    private function setDefaultOpts(): void
    {
        [$username, $password] = Cups::getAuth();

        $this->defaultOpts = RequestOptions::parse([
            'ip' => Cups::getIp(),
            'username' => $username,
            'password' => $password,
            'port' => Cups::getPort(),
            'secure' => Cups::getSecure(),
        ]);
    }

    private function guardAgainstInvalidConfig(#[SensitiveParameter] array $config): void
    {
        // IP Address
        throw_if(
            $config['ip'] !== null && ! is_string($config['ip']),
            InvalidArgumentException::class,
            'cups server ip must be null or a string',
        );

        throw_if(
            $config['ip'] !== null && ($config['ip'] === ''),
            InvalidArgumentException::class,
            'cups server ip cannot be an empty string',

        );

        throw_if(
            $config['ip'] !== null && (preg_match('/\s/', $config['ip'])),
            InvalidArgumentException::class,
            'cups server ip cannot contain whitespace',
        );

        // Check absence of extra keys
        $extraConfigKeys = array_diff(array_keys($config), array_keys(self::DEFAULT_CONFIG));
        throw_if(
            filled($extraConfigKeys),
            InvalidArgumentException::class,
            'Found unknown key(s) in configuration array: ' . "'" . implode("', '", $extraConfigKeys) . "'",
        );
    }
}
