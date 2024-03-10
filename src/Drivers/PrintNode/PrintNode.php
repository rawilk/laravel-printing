<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\PrintNode\Entity\Printer as PrintNodePrinter;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob as PrintNodePrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNode as PrintNodeApi;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer as RawilkPrinter;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob as RawilkPrintJob;

class PrintNode implements Driver
{
    use Macroable;

    protected PrintNodeApi $api;

    public function __construct()
    {
        $this->api = app(PrintNodeApi::class);
    }

    public function newPrintTask(): \Rawilk\Printing\Contracts\PrintTask
    {
        return new PrintTask($this->api);
    }

    public function printer($printerId = null): ?Printer
    {
        $printer = $this->api->printer((int) $printerId);

        if (! $printer) {
            return null;
        }

        return new RawilkPrinter($printer);
    }

    /**
     * @return \Illuminate\Support\Collection<int, RawilkPrinter>
     */
    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        return $this->api
            ->printers($limit, $offset, $dir)
            ->printers
            ->map(fn (PrintNodePrinter $p) => new RawilkPrinter($p));
    }

    public function printJob($jobId = null): ?PrintJob
    {
        $job = $this->api->printJob((int) $jobId);

        if (! $job) {
            return null;
        }

        return new RawilkPrintJob($job);
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        return $this->api
            ->printJobs($limit, $offset, $dir)
            ->jobs
            ->map(fn (PrintNodePrintJob $j) => new RawilkPrintJob($j));
    }

    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        return $this->api
            ->printerPrintJobs($printerId, $limit, $offset, $dir)
            ->jobs
            ->map(fn (PrintNodePrintJob $j) => new RawilkPrintJob($j));
    }

    public function printerPrintJob($printerId, $jobId): ?PrintJob
    {
        $job = $this->api->printerPrintJob((int) $printerId, (int) $jobId);

        if (! $job) {
            return null;
        }

        return new RawilkPrintJob($job);
    }
}
