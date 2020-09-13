<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use JsonSerializable;
use PrintNode\Client;
use PrintNode\Entity\Printer as PrintNodePrinter;
use Rawilk\Printing\Contracts\Printer as PrinterContract;

class Printer implements PrinterContract, Arrayable, JsonSerializable
{
    protected PrintNodePrinter $printer;
    protected Client $client;
    protected ?array $capabilities = null;

    public function __construct(PrintNodePrinter $printer, Client $client)
    {
        $this->printer = $printer;
        $this->client = $client;
    }

    public function capabilities(): array
    {
        if ($this->capabilities) {
            return $this->capabilities;
        }

        return $this->capabilities = json_decode(json_encode($this->printer->capabilities), true);
    }

    public function description(): ?string
    {
        return $this->printer->description;
    }

    public function id()
    {
        return (string) $this->printer->id;
    }

    public function isOnline(): bool
    {
        return $this->status() === 'online';
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
        return $this->printer->capabilities->bins;
    }

    public function jobs(): Collection
    {
        return collect([]);
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
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
