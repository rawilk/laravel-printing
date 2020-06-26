<?php

namespace Rawilk\Printing;

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;

class Printing implements Driver
{
    protected Driver $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
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
