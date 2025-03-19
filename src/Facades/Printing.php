<?php

declare(strict_types=1);

namespace Rawilk\Printing\Facades;

use Illuminate\Support\Facades\Facade;
use Rawilk\Printing\Enums\PrintDriver;

/**
 * @see \Rawilk\Printing\Printing
 *
 * @method static null|string|mixed defaultPrinterId()
 * @method static \Rawilk\Printing\Contracts\Printer|null defaultPrinter()
 * @method static \Rawilk\Printing\Contracts\PrintTask newPrintTask()
 * @method static \Rawilk\Printing\Contracts\Printer|null printer($printerId = null, ...$args)
 * @method static \Illuminate\Support\Collection printers(int|null $limit = null, int|null $offset = null, string|null $dir = null, ...$args)
 * @method static \Illuminate\Support\Collection printJobs(int|null $limit = null, int|null $offset = null, string|null $dir = null, ...$args)
 * @method static \Rawilk\Printing\Contracts\PrintJob|null printJob($jobId = null, ...$args)
 * @method static \Illuminate\Support\Collection printerPrintJobs($printerId, int|null $limit = null, int|null $offset = null, string|null $dir = null, ...$args)
 * @method static \Rawilk\Printing\Contracts\PrintJob|null printerPrintJob($printerId, $jobId, ...$args)
 * @method static \Rawilk\Printing\Printing driver(null|string|PrintDriver $driver = null)
 */
class Printing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Rawilk\Printing\Printing::class;
    }
}
