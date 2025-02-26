<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Rawilk\Printing\Api\Cups\Cups as CupsApi;
use Rawilk\Printing\Api\Cups\Operation;
use Rawilk\Printing\Api\Cups\Request;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Api\Cups\Types\Uri;
use Rawilk\Printing\Api\Cups\Version;
use Rawilk\Printing\Contracts\Driver;
use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\Cups\Entity\Printer as RawilkPrinter;

class Cups implements Driver
{
    private CupsApi $api;

    public function __construct()
    {
        $this->api = app(CupsApi::class);
    }

    public function newPrintTask(): \Rawilk\Printing\Contracts\PrintTask
    {
        return new PrintTask;
    }

    public function printer($printerId = null): ?Printer
    {
        $request = new Request;
        $request->setVersion(Version::V2_1)
            ->setOperation(Operation::GET_PRINTER_ATTRIBUTES)
            ->addOperationAttributes(['printer-uri' => new Uri($printerId)]);

        return $this->api->makeRequest($request)->getPrinters()->first();
    }

    /**
     * CUPS doesn't support limit, offset
     *
     * Printers have a lot of attributes, without the requested attributes filter
     * the request will be about 2x slower
     *
     * @return \Illuminate\Support\Collection<RawilkPrinter>
     */
    public function printers(?int $limit = null, ?int $offset = null, ?string $dir = null): \Illuminate\Support\Collection
    {
        $request = new Request;
        $request->setVersion(Version::V2_1)
            ->setOperation(Operation::CUPS_GET_PRINTERS);

        $printers = $this->api->makeRequest($request)->getPrinters();

        return $printers->slice($offset, $limit)->values();
    }

    public function printJob($jobId = null): ?PrintJob
    {
        $request = new Request;
        $request->setVersion(Version::V2_1)
            ->setOperation(Operation::GET_JOB_ATTRIBUTES)
            ->addOperationAttributes([
                'job-uri' => new Uri($jobId),
                'requested-attributes' => [
                    new Keyword('job-uri'),
                    new Keyword('job-state'),
                    new Keyword('number-of-documents'),
                    new Keyword('job-name'),
                    new Keyword('document-format'),
                    new Keyword('date-time-at-creation'),
                    new Keyword('job-printer-state-message'),
                    new Keyword('job-printer-uri'),
                ],
            ]);

        return $this->api->makeRequest($request)->getJobs()->first();
    }

    /**
     * Returns in-progress jobs
     */
    public function printerPrintJobs($printerId, ?int $limit = null, ?int $offset = null, ?string $dir = null): \Illuminate\Support\Collection
    {
        $request = new Request;
        $request->setVersion(Version::V2_1)
            ->setOperation(Operation::GET_JOBS)
            ->addOperationAttributes([
                'printer-uri' => new Uri($printerId),
                'which-jobs' => new Keyword('not-completed'),
                'requested-attributes' => [
                    new Keyword('job-uri'),
                    new Keyword('job-state'),
                    new Keyword('number-of-documents'),
                    new Keyword('job-name'),
                    new Keyword('document-format'),
                    new Keyword('date-time-at-creation'),
                    new Keyword('job-printer-state-message'),
                    new Keyword('job-printer-uri'),
                ],
            ]);

        return $this->api->makeRequest($request)->getJobs();
    }

    public function printerPrintJob($printerId, $jobId): ?PrintJob
    {
        return $this->printJob($jobId);
    }

    /**
     * @return \Illuminate\Support\Collection<\Rawilk\Printing\Contracts\PrintJob>
     */
    public function printJobs(?int $limit = null, ?int $offset = null, ?string $dir = null): \Illuminate\Support\Collection
    {
        $printerUris = $this->printers()->map(fn ($i) => $i->id());

        $jobs = collect();
        // Make request for each printer...
        $printerUris->each(
            function ($uri) use ($jobs) {
                $jobs->push(...$this->printerPrintJobs($uri));
            }
        );

        return $jobs;
    }
}
