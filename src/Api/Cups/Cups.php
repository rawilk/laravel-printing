<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Support\Facades\Http;

class Cups
{
    public function __construct(
        private string $ip,
        private ?string $username,
        private ?string $password,
        private int $port = 631,
        private bool $secure = false
    ) {}

    /**
     * @throws Illuminate\Http\Client\ConnectionException
     * @throws Illuminate\Http\Client\RequestException
     * @throws \Rawilk\Printing\Api\Cups\Exceptions\ClientException
     * @throws \Rawilk\Printing\Api\Cups\Exceptions\ServerException
     */
    public function makeRequest(Request $request): Response
    {
        $http = Http::withBody($request->encode());

        if ($this->username || $this->password) {
            $http->withBasicAuth($this->username, $this->password);
        }

        $http = $http->withHeaders(
            [
                'Content-Type' => 'application/ipp',
            ]
        )->post($this->getScheme() . '://' . $this->ip . ':' . $this->port . '/admin')
            ->throwIfClientError();
        $response = new Response($http->body());

        return $response;
    }

    private function getScheme()
    {
        return $this->secure ? 'https' : 'http';
    }
}
