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

    public function printer($printerId = null): null|Printer
    {
        $printer = $this->api->printer((int) $printerId);

        if (! $printer) {
            return null;
        }

        return new RawilkPrinter($printer);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection<int, RawilkPrinter>
     */
    public function printers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        return $this->api
            ->printers($limit, $offset, $dir)
            ->printers
            ->map(fn (PrintNodePrinter $p) => new RawilkPrinter($p));
    }

    public function printJob($jobId = null): null|PrintJob
    {
        $job = $this->api->printJob((int) $jobId);

        if (! $job) {
            return null;
        }

        return new RawilkPrintJob($job);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        return $this->api
            ->printJobs($limit, $offset, $dir)
            ->jobs
            ->map(fn (PrintNodePrintJob $j) => new RawilkPrintJob($j));
    }

    /**
     * @param $printerId
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection
     */
    public function printerPrintJobs($printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        return $this->api
            ->printerPrintJobs($printerId, $limit, $offset, $dir)
            ->jobs
            ->map(fn (PrintNodePrintJob $j) => new RawilkPrintJob($j));
    }

    public function printerPrintJob($printerId, $jobId): null|PrintJob
    {
        $job = $this->api->printerPrintJob((int) $printerId, (int) $jobId);

        if (! $job) {
            return null;
        }

        return new RawilkPrintJob($job);
    }
}
