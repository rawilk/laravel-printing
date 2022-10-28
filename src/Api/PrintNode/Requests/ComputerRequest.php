<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Computer;

class ComputerRequest extends PrintNodeRequest
{
    public function response(int $computerId): ?Computer
    {
        $computers = $this->getRequest("computers/{$computerId}");

        if (count($computers) === 0) {
            return null;
        }

        return new Computer($computers[0]);
    }
}
