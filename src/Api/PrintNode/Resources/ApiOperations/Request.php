<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\ApiOperations;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiRequestor;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Api\PrintNode\Util\Util;

/**
 * Trait for resources that can make API requests to PrintNode.
 * This trait should only be applied to classes that derive
 * from `PrintNodeApiResource`.
 *
 * @mixin \Rawilk\Printing\Api\PrintNode\PrintNodeApiResource
 */
trait Request
{
    protected static function _requestPage(
        string $url,
        ?array $params = null,
        null|array|RequestOptions $opts = null,
        ?string $expectedResource = null,
    ): Collection {
        /** @var \Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse $response */
        [$response, $opts] = static::_staticRequest('get', $url, $params, $opts);

        $expectedResource ??= static::class;

        $resources = Util::convertToPrintNodeObject($response->body, $opts, $expectedResource);

        return collect($resources)
            ->flatten()
            ->transform(function (PrintNodeApiResource $resource) use ($response) {
                $resource->setLastResponse($response);

                return $resource;
            });
    }

    protected static function _staticRequest(
        string $method,
        string $url,
        ?array $params = null,
        null|array|RequestOptions $opts = null,
    ): array {
        $opts = RequestOptions::parse($opts);
        $baseUrl = $opts->apiBase ?? static::baseUrl();

        $requestor = new PrintNodeApiRequestor($opts->apiKey, $baseUrl);
        [$opts->apiKey, $response] = $requestor->request($method, $url, $params, $opts->headers);

        return [$response, $opts];
    }

    protected function _request(
        string $method,
        string $url,
        ?array $params = null,
        null|array|RequestOptions $opts = null,
    ): array {
        $opts = $this->_opts->merge($opts);

        /** @var \Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse $response */
        [$response, $opts] = static::_staticRequest($method, $url, $params ?? [], $opts);

        $this->setLastResponse($response);

        return [$response->body, $opts];
    }
}
