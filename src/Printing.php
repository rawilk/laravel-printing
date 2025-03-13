<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Closure;
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

    protected ?Driver $temporaryDriver = null;

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

    /**
     * Use a specific driver on a single call.
     */
    public function driver(null|string|PrintDriver $driver = null): static
    {
        $this->temporaryDriver = app(Factory::class)->driver($driver);

        return $this;
    }

    public function getDriver(): Driver
    {
        return $this->getActiveDriver();
    }

    public function newPrintTask(): Contracts\PrintTask
    {
        return $this->executeDriverCall(
            fn (Driver $driver): Contracts\PrintTask => $driver->newPrintTask(),
        );
    }

    public function printer($printerId = null, ...$args): ?Printer
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?Printer => $driver->printer($printerId, ...$args),
        );
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\Printer>
     */
    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null, ...$args): Collection
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?Collection => $driver->printers($limit, $offset, $dir, ...$args),
        ) ?? collect();
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null, ...$args): Collection
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?Collection => $driver->printJobs($limit, $offset, $dir, ...$args),
        ) ?? collect();
    }

    public function printJob($jobId = null, ...$args): ?PrintJob
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?PrintJob => $driver->printJob($jobId, ...$args),
        );
    }

    /**
     * @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob>
     */
    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null, ...$args): Collection
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?Collection => $driver->printerPrintJobs($printerId, $limit, $offset, $dir, ...$args),
        ) ?? collect();
    }

    public function printerPrintJob($printerId, $jobId, ...$args): ?PrintJob
    {
        return $this->executeDriverCall(
            fn (Driver $driver): ?PrintJob => $driver->printerPrintJob($printerId, $jobId, ...$args),
        );
    }

    protected function executeDriverCall(Closure $callback): mixed
    {
        try {
            return $callback($this->getActiveDriver());
        } catch (Throwable $e) {
            static::getLogger()?->error($e->getMessage());

            return null;
        } finally {
            // Ensure the driver resets after a single call.
            $this->temporaryDriver = null;
        }
    }

    protected function getActiveDriver(): Driver
    {
        return $this->temporaryDriver ?? $this->driver;
    }
}
