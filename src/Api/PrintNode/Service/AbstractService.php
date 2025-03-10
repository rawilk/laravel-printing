<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Service;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PrintNodeClientInterface;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

/**
 * Abstract base class for all services.
 */
abstract class AbstractService
{
    public function __construct(protected PrintNodeClientInterface $client)
    {
    }

    /**
     * Get the client used by the services to send api requests.
     */
    public function getClient(): PrintNodeClientInterface
    {
        return $this->client;
    }

    protected function request(
        string $method,
        string $path,
        ?array $params = [],
        null|array|RequestOptions $opts = [],
        ?string $expectedResource = null,
    ) {
        return $this->getClient()->request($method, $path, $params ?? [], $opts ?? [], $expectedResource);
    }

    protected function requestCollection(
        string $method,
        string $path,
        ?array $params = [],
        null|array|RequestOptions $opts = [],
        ?string $expectedResource = null,
    ): Collection {
        return $this->getClient()->requestCollection($method, $path, $params ?? [], $opts ?? [], $expectedResource);
    }

    protected function buildPath(string $basePath, int ...$ids): string
    {
        $ids = implode(',', array_map('urlencode', $ids));

        return sprintf($basePath, $ids);
    }
}
