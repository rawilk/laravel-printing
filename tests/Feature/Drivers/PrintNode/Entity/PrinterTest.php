<?php

declare(strict_types=1);

namespace Rawilk\Printing\Tests\Feature\Drivers\PrintNode\Entity;

use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\Feature\Drivers\PrintNode\Fixtures\PrintNodePrinter;
use Rawilk\Printing\Tests\TestCase;

class PrinterTest extends TestCase
{
    protected PrintNode $printNode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->printNode = new PrintNode(config('printing.drivers.printnode.key'));
    }

    /** @test */
    public function can_be_cast_to_array(): void
    {
        $printer = $this->createPrinter();

        $toArray = $printer->toArray();

        $expected = [
            'id' => 'printer-id',
            'name' => 'printer name',
            'description' => 'printer description',
            'online' => true,
            'status' => 'online',
            'trays' => [
                'tray 1',
            ],
        ];

        self::assertNotEmpty($toArray);
        self::assertEquals($expected, $toArray);
    }

    /** @test */
    public function can_be_cast_to_json(): void
    {
        $printer = $this->createPrinter();

        $json = json_encode($printer);

        $expected = '{"id":"printer-id","name":"printer name","description":"printer description","online":true,"status":"online","trays":["tray 1"]}';

        self::assertEquals($expected, $json);
    }

    protected function createPrinter(): Printer
    {
        $printNodePrinter = new PrintNodePrinter($this->printNode->getClient());
        $printNodePrinter
            ->setId('printer-id')
            ->setDescription('printer description')
            ->setName('printer name');

        return new Printer($printNodePrinter, $this->printNode->getClient());
    }
}
