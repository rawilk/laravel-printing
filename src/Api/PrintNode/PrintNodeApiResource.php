<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Rawilk\Printing\Api\PrintNode\Exceptions\UnexpectedValue;

abstract class PrintNodeApiResource extends PrintNodeObject
{
    use Resources\ApiOperations\Request;

    public static function baseUrl(): string
    {
        return PrintNode::$apiBase;
    }

    public static function classUrl(): string
    {
        return str(class_basename(static::class))
            ->lower()
            ->append('s')
            ->prepend('/')
            ->toString();
    }

    public static function resourceUrl(?int $id = null): string
    {
        if ($id === null) {
            $class = static::class;

            throw new UnexpectedValue(
                'Could not determine which URL to request: ' .
                "{$class} instance has invalid ID: {$id}",
            );
        }

        $encodedId = urlencode((string) Util\Util::utf8($id));
        $base = static::classUrl();

        return "{$base}/{$encodedId}";
    }

    public function refresh(): static
    {
        $requestor = new PrintNodeApiRequestor($this->_opts->apiKey, static::baseUrl());
        $url = $this->instanceUrl();

        /** @var \Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse $response */
        [$this->_opts->apiKey, $response] = $requestor->request(
            'get',
            $url,
            headers: $this->_opts->headers,
        );

        $this->setLastResponse($response);

        // Most responses from PrintNode come as a collection, so we usually need
        // the first item.
        $data = Util\Util::isList($response->body)
            ? $response->body[0]
            : $response->body;

        $this->refreshFrom($data, $this->_opts);

        return $this;
    }

    /**
     * @return string the full API path for this API resource
     */
    public function instanceUrl(): string
    {
        return static::resourceUrl($this['id']);
    }

    protected static function buildPath(string $basePath, int ...$ids): string
    {
        $ids = implode(',', array_map('urlencode', $ids));

        return sprintf($basePath, $ids);
    }
}
