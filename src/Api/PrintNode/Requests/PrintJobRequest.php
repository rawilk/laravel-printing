<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJob;

class PrintJobRequest extends PrintNodeRequest
{
    public function response(int $jobId): ?PrintJob
    {
        $jobs = $this->getRequest("printjobs/{$jobId}");

        if (count($jobs) === 0) {
            return null;
        }

        return new PrintJob($jobs[0]);
    }
}
