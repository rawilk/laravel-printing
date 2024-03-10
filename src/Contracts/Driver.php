<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function newPrintTask(): PrintTask;

    public function printer($printerId = null): ?Printer;

    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection;

    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection;

    public function printJob($jobId = null): ?PrintJob;

    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection;

    public function printerPrintJob($printerId, $jobId): ?PrintJob;
}
