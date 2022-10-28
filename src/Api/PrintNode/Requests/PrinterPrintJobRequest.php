<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;

class PrinterPrintJobRequest extends PrintNodeRequest
{
    public function response(int $printerId, int $jobId): ?PrintJob
    {
        $jobs = $this->getRequest("printers/{$printerId}/printjobs/{$jobId}");

        if (count($jobs) === 0) {
            return null;
        }

        return new PrintJob($jobs[0]);
    }
}
