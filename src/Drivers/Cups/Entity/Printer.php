<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Rawilk\Printing\Contracts\Printer as PrinterContract;
use Rawilk\Printing\Drivers\Cups\Enum\PrinterState;
use Rawilk\Printing\Facades\Printing;

class Printer implements Arrayable, JsonSerializable, PrinterContract
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
            'name' => $this->name(),
            'description' => $this->description(),
            'online' => $this->isOnline(),
            'status' => $this->status(),
            'trays' => $this->trays(),
            'capabilities' => $this->capabilities(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * @param array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    public function capabilities(): array
    {
        return $this->attributes;
    }

    public function description(): ?string
    {
        return $this->attributes['printer-info']->value ?? null;
    }

    public function id()
    {
        // ID serves no purpose, return uri instead?
        $ids = $this->attributes['printer-uri-supported'];

        return is_array($ids) ? $ids[0] : $this->attributes['printer-uri-supported']->value;
    }

    public function isOnline(): bool
    {
        // Not sure
        return true;
    }

    public function name(): ?string
    {
        return $this->attributes['printer-name']->value ?? null;
    }

    public function status(): string
    {
        return strtolower(PrinterState::tryFrom($this->attributes['printer-state']->value)->name);
    }

    public function trays(): array
    {
        return $this->attributes['media-source-supported']->value ?? [];
    }

    public function jobs(): \Illuminate\Support\Collection
    {
        return Printing::printerPrintJobs($this->id());
    }
}
