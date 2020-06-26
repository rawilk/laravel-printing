<?php

namespace Rawilk\Printing\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function find($printerId = null): ?Printer;

    public function printers(): Collection;
}
