<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

interface PrintNodeClientInterface extends BasePrintNodeClientInterface
{
    /**
     * Sends a request to PrintNode's API.
     *
     * @param  string  $method  the HTTP method 'delete'|'get'|'post'
     * @param  string  $path  the path of the request
     * @param  array  $params  the parameters of the request
     * @param  array|RequestOptions  $opts  the special modifiers of the request
     * @param  null|class-string<\Rawilk\Printing\Api\PrintNode\PrintNodeObject>  $expectedResource  the object we should map the response into
     */
    public function request(
        string $method,
        string $path,
        array $params = [],
        array|RequestOptions $opts = [],
        ?string $expectedResource = null,
    );

    /**
     * Sends a request to PrintNode's API for a collection of resources.
     *
     * @param  string  $method  the HTTP method 'delete'|'get'|'post'
     * @param  string  $path  the path of the request
     * @param  array  $params  the parameters of the request
     * @param  array|RequestOptions  $opts  the special modifiers of the request
     * @param  null|class-string<\Rawilk\Printing\Api\PrintNode\PrintNodeObject>  $expectedResource  the object we should map each resource into
     */
    public function requestCollection(
        string $method,
        string $path,
        array $params = [],
        array|RequestOptions $opts = [],
        ?string $expectedResource = null,
    );
}
