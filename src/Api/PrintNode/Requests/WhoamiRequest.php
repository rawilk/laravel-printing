<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Whoami;

class WhoamiRequest extends PrintNodeRequest
{
    public function response(): Whoami
    {
        return new Whoami($this->getRequest('whoami'));
    }
}
