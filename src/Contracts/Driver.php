<?php

namespace Rawilk\Printing\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function newPrintTask(): PrintTask;

    public function printer($printerId = null): null|Printer;

    public function printers(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection;

    public function printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection;

    public function printJob($jobId = null): null|PrintJob;

    public function printerPrintJobs($printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null): Collection;

    public function printerPrintJob($printerId, $jobId): null|PrintJob;
}
