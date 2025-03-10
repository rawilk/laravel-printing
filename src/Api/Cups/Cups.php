<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\Cups\Exceptions\ServerError;

class Cups
{
    public function __construct(
        protected string $ip,
        protected ?string $username,
        protected ?string $password,
        protected int $port = 631,
        protected bool $secure = false
    ) {
    }

    public function makeRequest(Request $request): Response
    {
        $http = Http::withBody($request->encode())
            ->when(
                $this->username || $this->password,
                fn (Http $http) => $http->withBasicAuth($this->username ?? '', $this->password ?? ''),
            )
            ->withHeaders([
                'Content-Type' => 'application/ipp',
            ]);

        $response = $http->post($this->getAdminUrl())
            ->throwIfClientError();

        throw_unless(
            $response->ok(),
            new ServerError('Cups server request failed.'),
        );

        return new Response($response->body());
    }

    protected function getAdminUrl(): string
    {
        return $this->getScheme() . '://' . $this->ip . ':' . $this->port . '/admin';
    }

    protected function getScheme(): string
    {
        return $this->secure ? 'https' : 'http';
    }
}
