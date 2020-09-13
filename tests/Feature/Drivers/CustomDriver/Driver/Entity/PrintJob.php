<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\CustomDriver\Driver\Entity;

use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;

final class PrintJob implements PrintJobContract
{
    private Printer $printer;

    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function date()
    {
        return '';
    }

    public function id()
    {
        return 1;
    }

    public function name(): ?string
    {
        return 'job name';
    }

    public function printerId()
    {
        return $this->printer->id();
    }

    public function printerName(): ?string
    {
        return $this->printer->name();
    }

    public function state(): ?string
    {
        return 'success';
    }
}
