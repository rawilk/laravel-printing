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

it('lists an accounts printers', function () {
    $this->fakeRequest('printers', 'printers');

    $printers = $this->printNode->printers();

    $this->assertCount(24, $printers);
    $this->assertContainsOnlyInstancesOf(Printer::class, $printers);
});

test('finds an accounts printer', function () {
    $this->fakeRequest('printers/39', 'printer_single');

    $printer = $this->printNode->printer(39);

    $this->assertSame(39, $printer->id());
    $this->assertEquals(['Automatically Select'], $printer->trays());
    $this->assertEquals('Microsoft XPS Document Writer', $printer->name());
    $this->assertTrue($printer->isOnline());
});

test('returns null for no printer found', function () {
    $this->fakeRequest('printers/1234', 'printer_single_not_found');

    $printer = $this->printNode->printer(1234);

    $this->assertNull($printer);
});
