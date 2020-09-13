<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\Cups\Entity;

use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;
use Rawilk\Printing\Drivers\Cups\Support\Client;
use Rawilk\Printing\Tests\TestCase;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Model\Printer as CupsPrinter;
use Smalot\Cups\Transport\ResponseParser;

class JobTest extends TestCase
{
    protected JobManager $jobManager;

    protected function setUp(): void
    {
        parent::setUp();

        $client = new Client;
        $responseParser = new ResponseParser;
        $builder = new Builder;

        $this->jobManager = new JobManager($builder, $client, $responseParser);
    }

    /** @test */
    public function can_get_the_job_id(): void
    {
        self::assertSame(123456, $this->createJob()->id());
    }

    /** @test */
    public function can_get_the_job_name(): void
    {
        self::assertEquals('my print job', $this->createJob()->name());
    }

    /** @test */
    public function can_get_the_job_state(): void
    {
        self::assertEquals('success', $this->createJob()->state());
    }


    /** @test */
    public function can_get_the_printer_name_and_id(): void
    {
        $job = $this->createJob();

        self::assertEquals('printer-name', $job->printerName());
        self::assertEquals('localhost:631', $job->printerId());
    }

    protected function createJob(): PrintJob
    {
        $cupsJob = new Job;
        $cupsJob->setId(123456)
            ->setName('my print job')
            ->setState('success');

        return new PrintJob($cupsJob, $this->createPrinter());
    }

    protected function createPrinter(): Printer
    {
        $cupsPrinter = new CupsPrinter;
        $cupsPrinter->setName('printer-name')
            ->setUri('localhost:631')
            ->setStatus('online');

        return new Printer($cupsPrinter, $this->jobManager);
    }
}
