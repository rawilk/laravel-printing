<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Rawilk\Printing\Api\PrintNode\Exceptions\AuthenticationFailure;
use Rawilk\Printing\Api\PrintNode\Exceptions\PrintNodeApiRequestFailed;
use Rawilk\Printing\Api\PrintNode\Exceptions\RequestOptionsFoundInParams;
use Rawilk\Printing\Api\PrintNode\Exceptions\UnexpectedValue;
use Rawilk\Printing\Api\PrintNode\Util\Util;
use Rawilk\Printing\Exceptions\InvalidArgument;
use SensitiveParameter;

/** @internal */
class PrintNodeApiRequestor
{
    private ?PendingRequest $httpClient = null;

    private string $apiBase;

    private static array $optionsKeys = ['api_key', 'idempotency_key', 'api_base'];

    public function __construct(
        #[SensitiveParameter] private readonly ?string $apiKey = null,
        ?string $apiBase = null,
    ) {
        $apiBase ??= BasePrintNodeClient::API_BASE;

        $this->apiBase = $apiBase;
    }

    public function request(string $method, string $url, array $params = [], ?array $headers = []): array
    {
        [$absoluteUrl, $headers, $params, $apiKey] = $this->prepareRequest($method, $url, $params, $headers);

        // Sometimes null bytes can be included in paths, which can lead to cryptic server 400s.
        if (
            str_contains($absoluteUrl, "\0") ||
            str_contains($absoluteUrl, '%00')
        ) {
            throw new InvalidArgument("URLs may not contain null bytes ('\\0'); double check any IDs you're including with the request.");
        }

        $client = $this->httpClient()->withHeaders($headers);

        $response = match (strtolower($method)) {
            'get' => $client->get($absoluteUrl, $params),
            'post' => $client->post($absoluteUrl, $params),
            'delete' => $client->delete($absoluteUrl, $params),
        };

        $body = $this->interpretResponse($response);

        return [
            $apiKey,
            new PrintNodeApiResponse(
                code: $response->status(),
                body: $body,
                headers: $response->headers(),
            ),
        ];
    }

    private static function encodeObjects(mixed $objects): mixed
    {
        if ($objects instanceof PrintNodeApiResource) {
            return Util::utf8($objects->id);
        }

        if ($objects === true) {
            return 'true';
        }

        if ($objects === false) {
            return 'false';
        }

        if (is_array($objects)) {
            return array_map(function ($value) {
                return self::encodeObjects($value);
            }, $objects);
        }

        return Util::utf8($objects);
    }

    private static function defaultHeaders(string $apiKey): array
    {
        return [
            'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
        ];
    }

    private function httpClient(): PendingRequest
    {
        if (! $this->httpClient) {
            $this->httpClient = Http::acceptJson();
        }

        return $this->httpClient;
    }

    private function prepareRequest(string $method, string $url, ?array $params, ?array $headers): array
    {
        $myApiKey = $this->apiKey ?? PrintNode::getApiKey();

        throw_unless(
            filled($myApiKey),
            AuthenticationFailure::class,
            <<<'TXT'
            No API key provided. (Hint: set your API key using
            "PrintNode::setApiKey(<API-KEY>)")
            TXT
        );

        if ($params) {
            $optionKeysInParams = array_filter(
                self::$optionsKeys,
                fn (string $key): bool => array_key_exists($key, $params),
            );

            throw_if(
                count($optionKeysInParams) > 0,
                RequestOptionsFoundInParams::make($optionKeysInParams),
            );

            if ($method === 'get') {
                $this->normalizePaginationOptions($params);
            }
        }

        $absoluteUrl = $this->apiBase . $url;

        $params = static::encodeObjects($params);

        $defaultHeaders = static::defaultHeaders($myApiKey);
        $combinedHeaders = array_merge($defaultHeaders, $headers ?? []);
        if (! array_key_exists('X-Idempotency-Key', $combinedHeaders) && $method === 'post') {
            $combinedHeaders['X-Idempotency-Key'] = (string) Str::uuid();
        }

        return [$absoluteUrl, $combinedHeaders, $params, $myApiKey];
    }

    /**
     * Some requests only provide the integer of the resource created,
     * such as the requests to create a new print job.
     */
    private function interpretResponse(Response $response): array|int
    {
        if (! $response->successful()) {
            throw new PrintNodeApiRequestFailed(
                $response->json('message', ''),
                $response->status(),
            );
        }

        return $response->json();
    }

    private function normalizePaginationOptions(array &$params): void
    {
        if (array_key_exists('limit', $params)) {
            $params['limit'] = max($params['limit'], 1);
        }

        if (array_key_exists('offset', $params)) {
            $params['after'] = $params['offset'];

            unset($params['offset']);
        }

        if (array_key_exists('dir', $params)) {
            throw_unless(
                in_array($params['dir'], ['asc', 'desc']),
                UnexpectedValue::class,
                'Pagination sort direction must be either "asc" or "desc".',
            );
        }
    }
}
