<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\Cups\Entity\Printer as RawilkPrinter;
use Rawilk\Printing\Drivers\Cups\Support\Client;
use Rawilk\Printing\Exceptions\InvalidDriverConfig;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Printer as SmalotPrinter;
use Smalot\Cups\Transport\ResponseParser;

class Cups implements Driver
{
    use Macroable;

    protected Builder $builder;

    protected Client $client;

    protected ResponseParser $responseParser;

    protected PrinterManager $printerManager;

    protected JobManager $jobManager;

    public function __construct()
    {
        $this->client = new Client;
        $this->responseParser = new ResponseParser;
        $this->builder = new Builder(__DIR__ . '/config/');
    }

    public function remoteServer(string $ip, string $username, string $password, int $port = 631): void
    {
        if (! $username || ! $password) {
            throw InvalidDriverConfig::invalid('Remote CUPS server requires a username and password.');
        }

        $this->client = new Client(
            $username,
            $password,
            ['remote_socket' => "tcp://{$ip}:{$port}"]
        );
    }

    public function newPrintTask(): \Rawilk\Printing\Contracts\PrintTask
    {
        return new PrintTask($this->jobManager(), $this->printerManager());
    }

    public function printer($printerId = null): ?Printer
    {
        $printer = $this->printerManager()->findByUri($printerId);

        if ($printer) {
            return new RawilkPrinter($printer, $this->jobManager());
        }

        return null;
    }

    /** @return \Illuminate\Support\Collection<int, RawilkPrinter> */
    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        // TODO: find out if CUPS driver can paginate
        $printers = $this->printerManager()->getList();

        return collect($printers)
            ->map(fn (SmalotPrinter $printer) => new RawilkPrinter($printer, $this->jobManager()))
            ->values();
    }

    public function printJob($jobId = null): ?PrintJob
    {
        // TODO: Implement printJob() method.
        return null;
    }

    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        // TODO: Implement printerPrintJobs() method.
        return collect();
    }

    public function printerPrintJob($printerId, $jobId): ?PrintJob
    {
        // TODO: Implement printerPrintJob() method.
        return null;
    }

    /** @return \Illuminate\Support\Collection<int, \Rawilk\Printing\Contracts\PrintJob> */
    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): Collection
    {
        // TODO: implement printJobs() method.
        return collect();
    }

    protected function jobManager(): JobManager
    {
        if (! isset($this->jobManager)) {
            $this->jobManager = new JobManager(
                $this->builder,
                $this->client,
                $this->responseParser
            );
        }

        return $this->jobManager;
    }

    protected function printerManager(): PrinterManager
    {
        if (! isset($this->printerManager)) {
            $this->printerManager = new PrinterManager(
                $this->builder,
                $this->client,
                $this->responseParser
            );
        }

        return $this->printerManager;
    }
}
