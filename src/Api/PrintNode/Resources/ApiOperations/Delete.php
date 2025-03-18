<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\ApiOperations;

use Rawilk\Printing\Api\PrintNode\Exceptions\PrintNodeApiRequestFailed;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

/**
 * Adds a method to delete to PrintNode API resources that can be deleted.
 * This trait should only be applied to classes that derive from
 * `PrintNodeApiResource`
 *
 * @mixin \Rawilk\Printing\Api\PrintNode\PrintNodeApiResource
 */
trait Delete
{
    public function delete(?array $params = null, null|array|RequestOptions $opts = null): static
    {
        $url = $this->instanceUrl();

        [$response, $opts] = $this->_request('delete', $url, $params, $opts);

        // PrintNode sends an array of IDs that were affected in most DELETE requests.
        // If we don't receive the ID, something went wrong.
        throw_unless(
            is_array($response),
            PrintNodeApiRequestFailed::class,
            'Unexpected response received from PrintNode.',
        );

        throw_unless(
            in_array($this['id'], $response, true),
            PrintNodeApiRequestFailed::class,
            'Resource deletion failed.',
        );

        $this->refreshFrom($this->toArray(), $opts);

        return $this;
    }
}
