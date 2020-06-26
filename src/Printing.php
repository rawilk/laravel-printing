<?php

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

    public function find($printerId = null): ?Printer
    {
        try {
            return $this->driver->find($printerId);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function printers(): Collection
    {
        try {
            return $this->driver->printers();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }
}
