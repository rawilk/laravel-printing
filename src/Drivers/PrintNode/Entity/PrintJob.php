<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;

class PrintJob implements PrintJobContract
{
    public function __construct(protected PrintNodePrintJob $job) {}

    public function date()
    {
        return $this->job->createTimestamp;
    }

    public function id()
    {
        return $this->job->id;
    }

    public function name(): null|string
    {
        return $this->job->title;
    }

    public function printerId()
    {
        return optional($this->job->printer)->id;
    }

    public function printerName(): null|string
    {
        return optional($this->job->printer)->name;
    }

    public function state(): null|string
    {
        return $this->job->state;
    }
}
