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

    expect($printer)->toBeInstanceOf(Printer::class);
    expect($printer->id())->toBe(39);
    expect($printer->trays())->toEqual(['Automatically Select']);
    expect($printer->isOnline())->toBeTrue();
    expect($printer->name())->toEqual('Microsoft XPS Document Writer');
    expect($printer->description())->toEqual('Microsoft XPS Document Writer');
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
    expect($toArray)->toEqual($expected);
});
