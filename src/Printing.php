<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;

class Printing implements Driver
{
    protected Driver $driver;

    /** @var null|string|mixed */
    protected $defaultPrinterId;

    public function __construct(Driver $driver, $defaultPrinterId = null)
    {
        $this->driver = $driver;
        $this->defaultPrinterId = $defaultPrinterId;
    }

    public function defaultPrinter(): ?Printer
    {
        return $this->find($this->defaultPrinterId);
    }

    public function defaultPrinterId()
    {
        return $this->defaultPrinterId;
    }

    public function driver(?string $driver = null): self
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

    public function find($printerId = null): ?Printer
    {
        try {
            $printer = $this->driver->find($printerId);
        } catch (\Throwable $e) {
            $printer = null;
        }

        $this->resetDriver();

        return $printer;
    }

    public function printers(): Collection
    {
        try {
            $printers = $this->driver->printers();
        } catch (\Throwable $e) {
            $printers = collect([]);
        }

        $this->resetDriver();

        return $printers;
    }

    private function resetDriver(): void
    {
        $this->driver();
    }
}
