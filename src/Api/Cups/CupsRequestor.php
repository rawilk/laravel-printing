<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Http\Client\PendingRequest as HttpRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\Cups\Exceptions\CupsRequestFailed;
use Rawilk\Printing\Api\Cups\Exceptions\InvalidRequest;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use SensitiveParameter;

/** @internal */
class CupsRequestor
{
    private const CONTENT_TYPE = 'application/ipp';

    private ?HttpRequest $httpClient = null;

    public function __construct(
        private readonly ?string $ip = null,
        private readonly ?string $username = null,
        #[SensitiveParameter] private readonly ?string $password = null,
        private readonly ?int $port = null,
        private readonly ?bool $secure = null,
    ) {
    }

    public function request(
        string|HttpRequest $binary,
        RequestOptions $opts,
    ): CupsResponse {
        if ($binary instanceof HttpRequest) {
            $binary = $binary->encode();
        }

        [$adminUrl, $username, $password] = $this->prepareRequest();

        $client = $this->httpClient()
            ->withHeaders($opts->headers)
            ->withBody($binary, self::CONTENT_TYPE)
            ->when(
                filled($username) || filled($password),
                fn (HttpRequest $request) => $request->withBasicAuth($username ?? '', $password ?? ''),
            );

        $response = $client->post($adminUrl)->throwIfClientError();

        return new CupsResponse(
            code: $response->status(),
            body: $this->interpretResponse($response),
            headers: $response->headers(),
            opts: $opts,
        );
    }

    private function httpClient(): HttpRequest
    {
        if (! $this->httpClient) {
            $this->httpClient = Http::contentType(self::CONTENT_TYPE);
        }

        return $this->httpClient;
    }

    private function prepareRequest(): array
    {
        [$username, $password] = $this->getAuth();

        return [
            $this->getAdminUrl(),
            $username,
            $password,
        ];
    }

    private function interpretResponse(Response $response): string
    {
        if (! $response->successful()) {
            throw new CupsRequestFailed(
                code: $response->status(),
            );
        }

        return $response->body();
    }

    private function getAdminUrl(): string
    {
        $scheme = $this->getScheme();
        $ip = $this->getIp();
        $port = $this->getPort();

        return "{$scheme}://{$ip}:{$port}/admin";
    }

    private function getAuth(): array
    {
        [$cupsUsername, $cupsPassword] = Cups::getAuth();

        return [
            $this->username ?? $cupsUsername,
            $this->password ?? $cupsPassword,
        ];
    }

    private function getIp(): string
    {
        $myIp = $this->ip ?? Cups::getIp();

        throw_unless(
            filled($myIp),
            InvalidRequest::class,
            <<<'TXT'
            No CUPS IP address provided. (Hint: set your IP address
            using "Cups::setIp(<ip-address>)")
            TXT
        );

        return $myIp;
    }

    private function getPort(): int
    {
        $myPort = $this->port ?? Cups::getPort();

        throw_unless(
            filled($myPort) && is_int($myPort) && $myPort > 0,
            InvalidRequest::class,
            <<<'TXT'
            A positive integer must be used for the CUPS server port. (Hint:
            set your port using "Cups::setPort(<port>)")
            TXT
        );

        return $myPort;
    }

    private function getScheme(): string
    {
        $secure = $this->secure ?? Cups::getSecure();

        return $secure ? 'https' : 'http';
    }
}
