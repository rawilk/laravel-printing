<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer as PrinterContract;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob as PrintJobContract;
use SensitiveParameter;

class PrintNode implements Driver
{
    use Macroable;

    protected PrintNodeClient $client;

    public function __construct(#[SensitiveParameter] ?string $apiKey = null)
    {
        $this->client = app(PrintNodeClient::class, ['config' => ['api_key' => $apiKey]]);
    }

    public function getApiKey(): ?string
    {
        return $this->client->getApiKey();
    }

    public function setApiKey(?string $apiKey): static
    {
        $this->client->setApiKey($apiKey);

        return $this;
    }

    public function newPrintTask(): PrintTask
    {
        return new PrintTask($this->client);
    }

    public function printer($printerId = null, ?array $params = null, null|array|RequestOptions $opts = null): ?PrinterContract
    {
        $printer = $this->client->printers->retrieve((int) $printerId, $params, $opts);

        if (! $printer) {
            return null;
        }

        return new PrinterContract($printer);
    }

    public function printers(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        null|array|RequestOptions $opts = null,
    ): Collection {
        return $this->client->printers->all(
            params: static::formatPaginationParams($limit, $offset, $dir),
            opts: $opts,
        )->mapInto(PrinterContract::class);
    }

    public function printJobs(
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        null|array|RequestOptions $opts = null,
    ): Collection {
        return $this->client->printJobs->all(
            params: static::formatPaginationParams($limit, $offset, $dir),
            opts: $opts,
        )->mapInto(PrintJobContract::class);
    }

    public function printJob($jobId = null, ?array $params = null, null|array|RequestOptions $opts = null): ?PrintJobContract
    {
        $job = $this->client->printJobs->retrieve((int) $jobId, $params, $opts);

        if (! $job) {
            return null;
        }

        return new PrintJobContract($job);
    }

    public function printerPrintJobs(
        $printerId,
        ?int $limit = null,
        ?int $offset = null,
        ?string $dir = null,
        null|array|RequestOptions $opts = null,
    ): Collection {
        return $this->client->printers->printJobs(
            parentId: (int) $printerId,
            params: static::formatPaginationParams($limit, $offset, $dir),
            opts: $opts,
        )->mapInto(PrintJobContract::class);
    }

    public function printerPrintJob($printerId, $jobId, ?array $params = null, null|array|RequestOptions $opts = null): ?PrintJobContract
    {
        $job = $this->client->printers->printJob(
            parentId: (int) $printerId,
            printJobId: (int) $jobId,
            params: $params,
            opts: $opts,
        );

        if (! $job) {
            return null;
        }

        return new PrintJobContract($job);
    }

    protected static function formatPaginationParams(?int $limit, ?int $offset, ?string $dir): array
    {
        return array_filter([
            'limit' => $limit,
            'after' => $offset,
            'dir' => $dir,
        ]);
    }
}
