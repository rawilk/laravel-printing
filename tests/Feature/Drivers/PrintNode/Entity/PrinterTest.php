<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;
use Rawilk\Printing\Tests\TestCase;

uses(TestCase::class);
uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    $this->printNode = new PrintNode;
});

test('creates from api response', function () {
    $this->fakeRequest('printers/39', 'printer_single');

    $printer = $this->printNode->printer(39);

    $this->assertInstanceOf(Printer::class, $printer);
    $this->assertSame(39, $printer->id());
    $this->assertEquals(['Automatically Select'], $printer->trays());
    $this->assertTrue($printer->isOnline());
    $this->assertEquals('Microsoft XPS Document Writer', $printer->name());
    $this->assertEquals('Microsoft XPS Document Writer', $printer->description());
});

test('can be cast to array', function () {
    $this->fakeRequest('printers/39', 'printer_single');

    $printer = $this->printNode->printer(39);

    $toArray = $printer->toArray();

    $capabilities = $printer->capabilities();
    $expected = [
        'id' => 39,
        'name' => 'Microsoft XPS Document Writer',
        'description' => 'Microsoft XPS Document Writer',
        'online' => true,
        'status' => 'online',
        'trays' => [
            'Automatically Select',
        ],
        'capabilities' => $capabilities,
    ];

    $this->assertNotEmpty($toArray);
    $this->assertEquals($expected, $toArray);
});
