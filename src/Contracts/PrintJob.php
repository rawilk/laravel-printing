<?php

namespace Rawilk\Printing\Contracts;

interface PrintJob
{
    public function date();

    public function id();

    public function name(): null|string;

    public function printerId();

    public function printerName(): null|string;

    public function state(): null|string;
}
