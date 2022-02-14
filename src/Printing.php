<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Throwable;

class Printing implements Driver
{
    use Macroable;

    public function __construct(protected Driver $driver, protected mixed $defaultPrinterId = null)
    {
    }

    public function defaultPrinter(): null|Printer
    {
        return $this->printer($this->defaultPrinterId);
    }

    public function defaultPrinterId(): mixed
    {
        return $this->defaultPrinterId;
    }

    public function driver(null|string $driver = null): self
    {
        $this->driver = app('printing.factory')->driver($driver);

        return $this;
    }

    public function newPrintTask(): \Rawilk\Printing\Contracts\PrintTask
    {
        $task = $this->driver->newPrintTask();

        $this->resetDriver();

        return $task;
    }

    public function printer($printerId = null): null|Printer
    {
        try {
            $printer = $this->driver->printer($printerId);
        } catch (Throwable) {
            $printer = null;
        }

        $this->resetDriver();

        return $printer;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\Printer>
     */
    public function printers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        try {
            $printers = $this->driver->printers($limit, $offset, $dir);
        } catch (Throwable) {
            $printers = collect();
        }

        $this->resetDriver();

        return $printers;
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        try {
            $printJobs = $this->driver->printJobs($limit, $offset, $dir);
        } catch (Throwable) {
            $printJobs = collect();
        }

        $this->resetDriver();

        return $printJobs;
    }

    public function printJob($jobId = null): null|PrintJob
    {
        try {
            $job = $this->driver->printJob($jobId);
        } catch (Throwable) {
            $job = null;
        }

        $this->resetDriver();

        return $job;
    }

    /**
     * @param $printerId
     * @param int|null $limit
     * @param int|null $offset
     * @param string|null $dir
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printerPrintJobs($printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection
    {
        try {
            $printJobs = $this->driver->printerPrintJobs($printerId, $limit, $offset, $dir);
        } catch (Throwable) {
            $printJobs = collect();
        }

        $this->resetDriver();

        return $printJobs;
    }

    public function printerPrintJob($printerId, $jobId): null|PrintJob
    {
        try {
            $job = $this->driver->printerPrintJob($printerId, $jobId);
        } catch (Throwable) {
            $job = null;
        }

        $this->resetDriver();

        return $job;
    }

    private function resetDriver(): void
    {
        $this->driver();
    }
}
