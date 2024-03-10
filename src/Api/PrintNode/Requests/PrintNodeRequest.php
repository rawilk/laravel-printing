<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use Rawilk\Printing\Exceptions\PrintNodeApiRequestFailed;

abstract class PrintNodeRequest
{
    protected const BASE_URL = 'https://api.printnode.com/';

    protected $http;

    protected ?int $limit = null;

    /**
     * The ID after (or before, depending on $dir) which to start returning records.
     */
    protected ?int $offset = null;

    protected ?string $dir = null;

    public function __construct(protected string $apiKey)
    {
        $this->http = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
        ])->acceptJson();
    }

    public function postRequest(string $service, array $data = [])
    {
        $response = $this->http->post($this->endpoint($service), $data);

        if (! $response->successful()) {
            $this->handleFailedResponse($response);
        }

        return $response->json();
    }

    protected function endpoint(string $service): string
    {
        return $this->applyPaginationToUrl(static::BASE_URL . $service);
    }

    protected function getRequest(string $service): array
    {
        $response = $this->http->get($this->endpoint($service));

        if (! $response->successful()) {
            $this->handleFailedResponse($response);
        }

        return $response->json();
    }

    protected function applyPaginationToUrl(string $url): string
    {
        $args = [];

        if (! is_null($this->limit)) {
            $args['limit'] = max($this->limit, 1);
        }

        if (! is_null($this->offset)) {
            $args['after'] = $this->offset;
        }

        if (! is_null($this->dir)) {
            if ($this->dir !== 'asc' && $this->dir !== 'desc') {
                throw new InvalidArgumentException('Direction must be either "asc" or "desc"."');
            }

            $args['dir'] = $this->dir;
        }

        if (count($args) === 0) {
            return $url;
        }

        return $url . '?' . http_build_query($args);
    }

    protected function handleFailedResponse(Response $response): void
    {
        throw new PrintNodeApiRequestFailed($response->json('message', ''), $response->status());
    }
}
