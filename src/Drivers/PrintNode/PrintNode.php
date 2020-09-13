<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use Illuminate\Support\Collection;
use PrintNode\Client;
use PrintNode\Credentials\ApiKey;
use PrintNode\Entity\Printer as PrintNodePrinter;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer as RawilkPrinter;

class PrintNode implements Driver
{
    protected Client $client;

    public function __construct(string $apiKey)
    {
        $credentials = new ApiKey($apiKey);

        $this->client = new Client($credentials);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    // Method used for testing purposes.
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function newPrintTask(): \Rawilk\Printing\Contracts\PrintTask
    {
        return new PrintTask($this->client);
    }

    public function find($printerId = null): ?Printer
    {
        return $this
            ->printers()
            ->filter(fn (RawilkPrinter $p) => (string) $p->id() === (string) $printerId)
            ->first();
    }

    public function printers(): Collection
    {
        return collect($this->client->viewPrinters())
            ->map(fn (PrintNodePrinter $p) => new RawilkPrinter($p, $this->client))
            ->values();
    }
}
