<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Illuminate\Support\Collection;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
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

    public function find($printerId = null): ?Printer
    {
        $printer = $this->printerManager()->findByUri($printerId);

        if ($printer) {
            return new RawilkPrinter($printer, $this->jobManager());
        }

        return null;
    }

    public function printers(): Collection
    {
        $printers = $this->printerManager()->getList();

        return collect($printers)
            ->map(fn (SmalotPrinter $printer) => new RawilkPrinter($printer, $this->jobManager()))
            ->values();
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
