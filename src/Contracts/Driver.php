<?php

namespace Rawilk\Printing\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function newPrintTask(): PrintTask;

    public function find($printerId = null): ?Printer;

    public function printers(): Collection;
}
