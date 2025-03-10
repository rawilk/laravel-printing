<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Psr\Log\LoggerInterface;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Logger;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Enums\PrintDriver;
use Throwable;

class Printing implements Driver
{
    use Conditionable;
    use Macroable;

    public static null|LoggerInterface|Logger $logger = null;

    public function __construct(protected Driver $driver, protected mixed $defaultPrinterId = null)
    {
    }

    public static function getLogger(): null|LoggerInterface|Logger
    {
        return static::$logger;
    }

    public static function setLogger(LoggerInterface|Logger $logger): void
    {
        static::$logger = $logger;
    }

    public function defaultPrinter(): ?Printer
    {
        return $this->printer($this->defaultPrinterId);
    }

    public function defaultPrinterId(): mixed
    {
        return $this->defaultPrinterId;
    }

    public function driver(null|string|PrintDriver $driver = null): static
    {
        $this->driver = app(Factory::class)->driver($driver);

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
        } catch (Throwable $e) {
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
