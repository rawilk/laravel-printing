<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\ApiOperations;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

/**
 * For listable resources, add a static method to retrieve them all.
 * This trait should only be applied to classes that derive
 * form `PrintNodeApiResource`.
 *
 * @mixin \Rawilk\Printing\Api\PrintNode\PrintNodeApiResource
 * @mixin Request
 */
trait All
{
    public static function all(?array $params = [], null|array|RequestOptions $opts = null): Collection
    {
        $url = static::classUrl();

        return static::_requestPage($url, $params, $opts, static::class);
    }
}
