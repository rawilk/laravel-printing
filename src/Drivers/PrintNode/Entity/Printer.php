<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\PrintNode\Resources\Printer as PrintNodePrinter;
use Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Concerns\SerializesToJson;
use Rawilk\Printing\Contracts\Printer as PrinterContract;

class Printer implements PrinterContract
{
    use Macroable;
    use SerializesToJson;

    public function __construct(protected readonly PrintNodePrinter $printer)
    {
    }

    public function __debugInfo(): ?array
    {
        return $this->printer->__debugInfo();
    }

    public function printer(): PrintNodePrinter
    {
        return $this->printer;
    }

    public function capabilities(): array
    {
        return $this->printer->capabilities->toArray();
    }

    public function printerCapabilities(): PrinterCapabilities
    {
        return $this->printer->capabilities;
    }

    public function description(): ?string
    {
        return $this->printer->description;
    }

    public function id(): int
    {
        return $this->printer->id;
    }

    public function isOnline(): bool
    {
        return $this->printer->isOnline();
    }

    public function name(): ?string
    {
        return $this->printer->name;
    }

    public function status(): string
    {
        return $this->printer->state;
    }

    public function trays(): array
    {
        return $this->printer->trays();
    }

    /**
     * @return Collection<int, PrintJob>
     */
    public function jobs(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        return $this->printer->printJobs($params, $opts)->mapInto(PrintJob::class);
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
