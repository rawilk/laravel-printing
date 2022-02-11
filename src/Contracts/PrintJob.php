<?php

namespace Rawilk\Printing\Contracts;

use Carbon\Carbon;

interface PrintJob
{
    public function date(): null|Carbon;

    public function id();

    public function name(): null|string;

    public function printerId();

    public function printerName(): null|string;

    public function state(): null|string;
}
