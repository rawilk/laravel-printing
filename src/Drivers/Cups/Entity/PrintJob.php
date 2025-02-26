<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Rawilk\Printing\Contracts\PrintJob as PrintJobContract;
use Rawilk\Printing\Drivers\Cups\Enum\JobState;

class PrintJob implements Arrayable, JsonSerializable, PrintJobContract
{
    /**
     * @param array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    protected array $attributes;

    /**
     * @param array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    public function __construct(array $printerAttributes)
    {
        $this->attributes = $printerAttributes;
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'date' => $this->date(),
            'name' => $this->name(),
            'printerId' => $this->printerId(),
            'printerName' => $this->printerName(),
            'state' => $this->state(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function date(): ?Carbon
    {
        return $this->attributes['date-time-at-creation']->value ?? null;
    }

    public function id()
    {
        // Id serves no purpose, return uri instead?
        return $this->attributes['job-uri']->value ?? null;
    }

    public function name(): ?string
    {
        return $this->attributes['job-name']->value ?? null;
    }

    public function printerId()
    {
        return $this->attributes['job-printer-uri']->value ?? null;
    }

    public function printerName(): ?string
    {
        // Extract name from uri
        if (preg_match('/printers\/(.*)$/', $this->printerId(), $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function state(): ?string
    {
        return strtolower(JobState::tryFrom($this->attributes['job-state']->value)->name);
    }
}
