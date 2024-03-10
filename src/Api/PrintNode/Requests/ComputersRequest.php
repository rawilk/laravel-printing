<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Computers;

class ComputersRequest extends PrintNodeRequest
{
    public function response(?int $limit = null, ?int $offset = null, ?string $dir = null): Computers
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->dir = $dir;

        $computers = $this->getRequest('computers');

        return (new Computers)->setComputers($computers);
    }
}
