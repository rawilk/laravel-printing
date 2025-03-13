<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\Cups\Resources\Printer as CupsPrinter;
use Rawilk\Printing\Concerns\SerializesToJson;
use Rawilk\Printing\Contracts\Printer as PrinterContract;
use Rawilk\Printing\Facades\Printing;

class Printer implements PrinterContract
{
    use Macroable;
    use SerializesToJson;

    public function __construct(protected readonly CupsPrinter $printer)
    {
    }

    public function __debugInfo(): ?array
    {
        return $this->printer->__debugInfo();
    }

    public function printer(): CupsPrinter
    {
        return $this->printer;
    }

    /**
     * @return array<string, \Rawilk\Printing\Api\Cups\Type|array>
     */
    public function capabilities(): array
    {
        return $this->printer->capabilities();
    }

    public function description(): ?string
    {
        return $this->printer->printerInfo;
    }

    public function id(): string
    {
        return $this->printer->uri;
    }

    public function isOnline(): bool
    {
        return $this->printer->isOnline();
    }

    public function name(): ?string
    {
        return $this->printer->printerName;
    }

    public function status(): string
    {
        return $this->printer->state()?->name;
    }

    public function trays(): array
    {
        return $this->printer->trays();
    }

    public function jobs(): Collection
    {
        return Printing::printerPrintJobs($this->id());
    }

    public function toArray(): array
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
}
