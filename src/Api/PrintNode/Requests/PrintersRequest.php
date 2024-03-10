<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Printers;

class PrintersRequest extends PrintNodeRequest
{
    public function response(?int $limit = null, ?int $offset = null, ?string $dir = null): Printers
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->dir = $dir;

        $printers = $this->getRequest('printers');

        return (new Printers)->setPrinters($printers);
    }
}
