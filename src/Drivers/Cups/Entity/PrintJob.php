<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;
use Smalot\Cups\Model\JobInterface;

class PrintJob implements PrintJobContract
{
    public function __construct(protected JobInterface $job, protected null|Printer $printer = null) {}

    public function date()
    {
        // Not sure if it is possible to retrieve the date.
        return null;
    }

    public function id()
    {
        return $this->job->getId();
    }

    public function name(): null|string
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

    public function printerName(): null|string
    {
        if ($this->printer) {
            return $this->printer->name();
        }

        return null;
    }

    public function state(): null|string
    {
        return $this->job->getState();
    }
}
