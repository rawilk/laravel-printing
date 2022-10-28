<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\Printer;

class PrinterRequest extends PrintNodeRequest
{
    public function response(int $printerId): ?Printer
    {
        $printers = $this->getRequest("printers/{$printerId}");

        if (count($printers) === 0) {
            return null;
        }

        return new Printer($printers[0]);
    }
}
