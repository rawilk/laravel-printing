<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Carbon\Carbon;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;
use Smalot\Cups\Model\JobInterface;

class PrintJob implements PrintJobContract
{
    use Macroable;

    public function __construct(protected JobInterface $job, protected ?Printer $printer = null)
    {
    }

    public function date(): ?Carbon
    {
        // Not sure if it is possible to retrieve the date.
        return null;
    }

    public function id()
    {
        return $this->job->getId();
    }

    public function name(): ?string
    {
        return $this->job->getName();
    }

    public function printerId()
    {
        if ($this->printer) {
            return $this->printer->id();
        }

        return null;
    }

    public function printerName(): ?string
    {
        if ($this->printer) {
            return $this->printer->name();
        }

        return null;
    }

    public function state(): ?string
    {
        return $this->job->getState();
    }
}
