<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

use Carbon\Carbon;

interface PrintJob
{
    public function date(): ?Carbon;

    public function id();

    public function name(): ?string;

    public function printerId();

    public function printerName(): ?string;

    public function state(): ?string;
}
