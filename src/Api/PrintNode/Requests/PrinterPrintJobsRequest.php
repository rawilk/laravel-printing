<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Requests;

use Rawilk\Printing\Api\PrintNode\Entity\PrintJobs;

class PrinterPrintJobsRequest extends PrintNodeRequest
{
    public function response(int $printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): PrintJobs
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->dir = $dir;

        $printJobs = $this->getRequest("printers/{$printerId}/printjobs");

        return (new PrintJobs)->setJobs($printJobs);
    }
}
