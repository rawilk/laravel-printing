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

    public function defaultPrinter(): ?Printer
    {
        return $this->printer($this->defaultPrinterId);
    }

    public function defaultPrinterId(): mixed
    {
        return $this->defaultPrinterId;
    }

    public function driver(?string $driver = null): self
    {
        $this->driver = app('printing.factory')->driver($driver);

        return $this;
    }

    public function newPrintTask(): Contracts\PrintTask
    {
        $task = $this->driver->newPrintTask();

        $this->resetDriver();

        return $task;
    }

    public function printer($printerId = null): ?Printer
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
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\Printer>
     */
    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
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
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        try {
            $printJobs = $this->driver->printJobs($limit, $offset, $dir);
        } catch (Throwable) {
            $printJobs = collect();
        }

        $this->resetDriver();

        return $printJobs;
    }

    public function printJob($jobId = null): ?PrintJob
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
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        try {
            $printJobs = $this->driver->printerPrintJobs($printerId, $limit, $offset, $dir);
        } catch (Throwable) {
            $printJobs = collect();
        }

        $this->resetDriver();

        return $printJobs;
    }

    public function printerPrintJob($printerId, $jobId): ?PrintJob
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
