<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Illuminate\Support\Traits\Macroable;

class PrintNode
{
    use Macroable;

    public function __construct(private string $apiKey)
    {
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function computers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Entity\Computers
    {
        return (new Requests\ComputersRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function computer(int $computerId): null|Entity\Computer
    {
        return (new Requests\ComputerRequest($this->apiKey))->response($computerId);
    }

    public function printers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Entity\Printers
    {
        return (new Requests\PrintersRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function printer(int $printerId): null|Entity\Printer
    {
        return (new Requests\PrinterRequest($this->apiKey))->response($printerId);
    }

    public function whoami(): Entity\Whoami
    {
        return (new Requests\WhoamiRequest($this->apiKey))->response();
    }

    public function createPrintJob(Entity\PrintJob $job): Entity\PrintJob
    {
        return (new Requests\CreatePrintJobRequest($this->apiKey))->send($job);
    }

    public function printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null): Entity\PrintJobs
    {
        return (new Requests\PrintJobsRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function printJob(int $jobId): null|Entity\PrintJob
    {
        return (new Requests\PrintJobRequest($this->apiKey))->response($jobId);
    }

    public function printerPrintJobs(int $printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): Entity\PrintJobs
    {
        return (new Requests\PrinterPrintJobsRequest($this->apiKey))->response($printerId, $limit, $offset, $dir);
    }

    public function printerPrintJob(int $printerId, int $jobId): null|Entity\PrintJob
    {
        return (new Requests\PrinterPrintJobRequest($this->apiKey))->response($printerId, $jobId);
    }
}
