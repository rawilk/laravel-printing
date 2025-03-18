<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use JsonSerializable;

interface Printer extends Arrayable, JsonSerializable
{
    public function capabilities(): array;

    public function description(): ?string;

    public function id();

    public function isOnline(): bool;

    public function name(): ?string;

    public function status(): string;

    public function trays(): array;

    public function jobs(): Collection;
}
