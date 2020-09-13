<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;

class PrintJob implements PrintJobContract
{
    protected PrintNodePrintJob $job;

    public function __construct(PrintNodePrintJob $job)
    {
        $this->job = $job;
    }

    public function date()
    {
        return $this->job->createTimestamp;
    }

    public function id()
    {
        return $this->job->id;
    }

    public function name(): ?string
    {
        return $this->job->title;
    }

    public function printerId()
    {
        return optional($this->job->printer)->id;
    }

    public function printerName(): ?string
    {
        return optional($this->job->printer)->name;
    }

    public function state(): ?string
    {
        return $this->job->state;
    }
}
