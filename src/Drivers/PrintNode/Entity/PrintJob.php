<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Carbon\Carbon;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob as PrintNodePrintJob;
use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;

class PrintJob implements PrintJobContract
{
    use Macroable;

    public ?Printer $printer = null;

    public function __construct(protected PrintNodePrintJob $job)
    {
        if ($job->printer) {
            $this->printer = new Printer($job->printer);
        }
    }

    public function job(): PrintNodePrintJob
    {
        return $this->job;
    }

    public function date(): ?Carbon
    {
        return $this->job->created;
    }

    public function id(): int
    {
        return $this->job->id;
    }

    public function name(): ?string
    {
        return $this->job->title;
    }

    public function printerId(): int|string
    {
        return $this->job->printer?->id;
    }

    public function printerName(): ?string
    {
        return $this->job->printer?->name;
    }

    public function state(): ?string
    {
        return $this->job->state;
    }
}
