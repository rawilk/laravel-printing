<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

interface PrintJob extends Arrayable, JsonSerializable
{
    public function date(): ?CarbonInterface;

    public function id();

    public function name(): ?string;

    public function printerId();

    public function printerName(): ?string;

    public function state(): ?string;
}
