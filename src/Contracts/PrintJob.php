<?php

namespace Rawilk\Printing\Contracts;

interface PrintJob
{
    public function date();

    public function id();

    public function name(): ?string;

    public function printerId();

    public function printerName(): ?string;

    public function state(): ?string;
}
