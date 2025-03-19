<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Fixtures\Drivers\Custom\Entity;

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Printer as PrinterContract;

final class Printer implements PrinterContract
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function capabilities(): array
    {
        return $this->data['capabilities'];
    }

    public function description(): ?string
    {
        return $this->data['description'];
    }

    public function id(): string
    {
        return $this->data['id'];
    }

    public function isOnline(): bool
    {
        return $this->status() === 'online';
    }

    public function name(): ?string
    {
        return $this->data['name'];
    }

    public function status(): string
    {
        return $this->data['status'];
    }

    public function trays(): array
    {
        return $this->capabilities()['trays'] ?? [];
    }

    public function jobs(): Collection
    {
        return collect();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'online' => $this->isOnline(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
