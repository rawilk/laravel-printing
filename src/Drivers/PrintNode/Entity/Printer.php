<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Rawilk\Printing\Api\PrintNode\Entity\Printer as PrintNodePrinter;
use Rawilk\Printing\Api\PrintNode\Entity\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Contracts\Printer as PrinterContract;

class Printer implements Arrayable, JsonSerializable, PrinterContract
{
    use Macroable;

    protected ?array $capabilities = null;

    public function __construct(protected PrintNodePrinter $printer)
    {
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

    public function jobs(?int $limit = null, ?int $offset = null, ?string $dir = null, ?string $apiKey = null): Collection
    {
        $api = app(PrintNode::class);

        if ($apiKey) {
            $api->setApiKey($apiKey);
        }

        $printJobs = $api->printerPrintJobs($this->id(), $limit, $offset, $dir);

        return $printJobs->jobs->map(fn ($job) => new PrintJob($job));
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

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
