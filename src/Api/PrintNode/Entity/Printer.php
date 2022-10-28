<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Carbon\Carbon;

class Printer extends Entity
{
    public string|int $id;

    public ?string $name = null;

    public ?string $state = null;

    public ?string $description = null;

    public bool $default = false;

    public PrinterCapabilities $capabilities;

    public Computer $computer;

    public ?Carbon $created = null;

    public function __construct(array $data)
    {
        $this->capabilities = new PrinterCapabilities([]);
        $this->computer = new Computer([]);

        parent::__construct($data);
    }

    public function setCapabilities(?array $capabilities): self
    {
        if (is_array($capabilities)) {
            $this->capabilities = new PrinterCapabilities($capabilities);
        }

        return $this;
    }

    public function setComputer(array $data): self
    {
        $this->computer = new Computer($data);

        return $this;
    }

    public function setCreateTimestamp($timestamp): self
    {
        $this->created = $this->getTimestamp($timestamp);

        return $this;
    }

    public function setDefault($default): self
    {
        if (is_null($default)) {
            $default = false;
        }

        $this->default = $default;

        return $this;
    }

    public function copies(): int
    {
        return $this->capabilities->copies;
    }

    public function isColor(): bool
    {
        return $this->capabilities->color;
    }

    public function isCollate(): bool
    {
        return $this->capabilities->collate;
    }

    public function isDuplex(): bool
    {
        return $this->capabilities->duplex;
    }

    public function medias(): array
    {
        return $this->capabilities->medias;
    }

    public function bins(): array
    {
        return $this->capabilities->trays();
    }

    // Alias for bins()
    public function trays(): array
    {
        return $this->bins();
    }

    public function isOnline(): bool
    {
        return strtolower($this->state) === 'online';
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'createTimestamp' => $this->created,
        ]);
    }
}
