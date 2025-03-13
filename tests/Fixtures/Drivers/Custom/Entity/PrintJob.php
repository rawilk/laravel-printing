<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Fixtures\Drivers\Custom\Entity;

use Carbon\Carbon;
use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;

final class PrintJob implements PrintJobContract
{
    private Printer $printer;

    public function __construct(Printer $printer)
    {
        $this->printer = $printer;
    }

    public function date(): ?Carbon
    {
        return null;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'printerId' => $this->printerId(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
