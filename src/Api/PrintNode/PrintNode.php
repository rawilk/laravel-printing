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

    public function computers(?int $limit = null, ?int $offset = null, ?string $dir = null): Entity\Computers
    {
        return (new Requests\ComputersRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function computer(int $computerId): ?Entity\Computer
    {
        return (new Requests\ComputerRequest($this->apiKey))->response($computerId);
    }

    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Entity\Printers
    {
        return (new Requests\PrintersRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function printer(int $printerId): ?Entity\Printer
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

    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Entity\PrintJobs
    {
        return (new Requests\PrintJobsRequest($this->apiKey))->response($limit, $offset, $dir);
    }

    public function printJob(int $jobId): ?Entity\PrintJob
    {
        return (new Requests\PrintJobRequest($this->apiKey))->response($jobId);
    }

    public function printerPrintJobs(int $printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Entity\PrintJobs
    {
        return (new Requests\PrinterPrintJobsRequest($this->apiKey))->response($printerId, $limit, $offset, $dir);
    }

    public function printerPrintJob(int $printerId, int $jobId): ?Entity\PrintJob
    {
        return (new Requests\PrinterPrintJobRequest($this->apiKey))->response($printerId, $jobId);
    }
}
