<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\Cups\CupsClient;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\Printer as PrinterContract;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob as PrintJobContract;
use SensitiveParameter;

class Cups implements Driver
{
    use Macroable;

    protected CupsClient $client;

    public function __construct(#[SensitiveParameter] ?array $config = [])
    {
        $this->client = app(CupsClient::class, ['config' => $config]);
    }

    public function getConfig(): array
    {
        return $this->client->getConfig();
    }

    public function newPrintTask(): PrintTask
    {
        return new PrintTask($this->client);
    }

    public function printer($printerId = null, array $params = [], array|null|RequestOptions $opts = null): ?PrinterContract
    {
        $printer = $this->client->printers->retrieve($printerId, $params, $opts);

        if (! $printer) {
            return null;
        }

        return new PrinterContract($printer);
    }

    /**
     * CUPS doesn't support limit, offset
     *
     * Printers have a lot of attributes, without the requested attributes filter
     * the request will be about 2x slower
     *
     * @return \Illuminate\Support\Collection<int, PrinterContract>
     */
    public function printers(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        array $params = [],
        array|null|RequestOptions $opts = null,
    ): Collection {
        $printers = $this->client->printers->all($params, $opts);

        return $printers
            ->slice($offset ?? 0, $limit)
            ->values()
            ->mapInto(PrinterContract::class);
    }

    public function printJob($jobId = null, array $params = [], array|null|RequestOptions $opts = null): ?PrintJobContract
    {
        $job = $this->client->printJobs->retrieve($jobId, $params, $opts);

        if (! $job) {
            return null;
        }

        return new PrintJobContract($job);
    }

    /**
     * Note: $limit, $offset, $dir do nothing currently.
     */
    public function printerPrintJobs(
        $printerId,
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        array $params = [],
        array|null|RequestOptions $opts = null,
    ): Collection {
        return $this->client->printers->printJobs(
            parentUri: $printerId,
            params: $params,
            opts: $opts,
        )->mapInto(PrintJobContract::class);
    }

    /**
     * There isn't really a way to do this with CUPS, but the normal `printJob()` method call
     * should yield the same result anyway.
     */
    public function printerPrintJob($printerId, $jobId, array|null|RequestOptions $opts = null): ?PrintJobContract
    {
        return $this->printJob($jobId, $opts);
    }

    /**
     * Note: $limit, $offset occurs on the client side, $dir does nothing currently.
     *
     * @return \Illuminate\Support\Collection<PrintJobContract>
     */
    public function printJobs(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        array $params = [],
        array|null|RequestOptions $opts = null,
    ): Collection {
        return $this->printers(
            params: $params,
            opts: $opts,
        )
            ->map(
                fn (Printer $printer) => $this->printerPrintJobs($printer->id(), params: $params, opts: $opts)
            )->flatten(1)->skip($offset)->take($limit)->values();
    }
}
