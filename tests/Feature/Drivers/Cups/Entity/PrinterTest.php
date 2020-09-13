<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\Cups\Entity;

use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Support\Client;
use Rawilk\Printing\Tests\TestCase;
use Smalot\Cups\Builder\Builder;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Model\Printer as CupsPrinter;
use Smalot\Cups\Transport\ResponseParser;

class PrinterTest extends TestCase
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
    public function can_be_cast_to_array(): void
    {
        $printer = $this->createPrinter();

        $toArray = $printer->toArray();

        $expected = [
            'id' => 'localhost:631',
            'name' => 'printer-name',
            'description' => null,
            'online' => true,
            'status' => 'online',
            'trays' => [],
        ];

        self::assertNotEmpty($toArray);
        self::assertEquals($expected, $toArray);
    }

    /** @test */
    public function can_be_cast_to_json(): void
    {
        $printer = $this->createPrinter();

        $json = json_encode($printer);

        $expected = json_encode([
            'id' => 'localhost:631',
            'name' => 'printer-name',
            'description' => null,
            'online' => true,
            'status' => 'online',
            'trays' => [],
        ]);

        self::assertEquals($expected, $json);
    }

    /** @test */
    public function can_get_the_id_of_the_printer(): void
    {
        self::assertEquals('localhost:631', $this->createPrinter()->id());
    }

    /** @test */
    public function can_get_the_status_of_the_printer(): void
    {
        $printer = $this->createPrinter();

        self::assertTrue($printer->isOnline());
        self::assertEquals('online', $printer->status());

        $printer->cupsPrinter()->setStatus('offline');

        self::assertFalse($printer->isOnline());
    }

    /** @test */
    public function can_get_printer_description(): void
    {
        $printer = $this->createPrinter();

        $printer->cupsPrinter()->setAttribute('printer-info', 'Some description');

        self::assertEquals('Some description', $printer->description());
    }

    /** @test */
    public function can_get_the_printers_trays(): void
    {
        $printer = $this->createPrinter();

        self::assertCount(0, $printer->trays());

        // Capabilities is cached after first retrieval, so we'll just use a fresh instance to test this
        $printer = $this->createPrinter();

        $printer->cupsPrinter()->setAttribute('media-source-supported', ['Tray 1']);

        self::assertCount(1, $printer->trays());
        self::assertEquals('Tray 1', $printer->trays()[0]);
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
