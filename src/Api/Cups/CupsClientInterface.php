<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Util\RequestOptions;

interface CupsClientInterface extends BaseCupsClientInterface
{
    /**
     * Send a request to the CUPS server.
     *
     * @param  string|PendingRequest  $binary  an encoded string of the data to send
     * @param  array|RequestOptions  $opts  the special modifiers of the request
     */
    public function request(string|PendingRequest $binary, array|RequestOptions $opts = []);
}
