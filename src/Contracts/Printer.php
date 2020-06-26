<?php

namespace Rawilk\Printing\Contracts;

use Illuminate\Support\Collection;

interface Printer
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
