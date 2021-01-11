<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Throwable;

class Printing implements Driver
{
    public function __construct(protected Driver $driver, protected mixed $defaultPrinterId = null) {}

    public function defaultPrinter(): ?Printer
    {
        return $this->find($this->defaultPrinterId);
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

    public function find($printerId = null): null|Printer
    {
        try {
            $printer = $this->driver->find($printerId);
        } catch (Throwable) {
            $printer = null;
        }

        $this->resetDriver();

        return $printer;
    }

    public function printers(): Collection
    {
        try {
            $printers = $this->driver->printers();
        } catch (Throwable) {
            $printers = collect();
        }

        $this->resetDriver();

        return $printers;
    }

    private function resetDriver(): void
    {
        $this->driver();
    }
}
