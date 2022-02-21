<?php

declare(strict_types=1);

use Rawilk\Printing\Drivers\PrintNode\Entity\Printer;
use Rawilk\Printing\Drivers\PrintNode\PrintNode;
use Rawilk\Printing\Tests\Concerns\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    $this->printNode = new PrintNode;
});

it('lists an accounts printers', function () {
    $this->fakeRequest('printers', 'printers');

    $printers = $this->printNode->printers();

    expect($printers)->toHaveCount(24);
    $this->assertContainsOnlyInstancesOf(Printer::class, $printers);
});

test('finds an accounts printer', function () {
    $this->fakeRequest('printers/39', 'printer_single');

    $printer = $this->printNode->printer(39);

    expect($printer->id())->toBe(39);
    expect($printer->trays())->toEqual(['Automatically Select']);
    expect($printer->name())->toEqual('Microsoft XPS Document Writer');
    expect($printer->isOnline())->toBeTrue();
});

test('returns null for no printer found', function () {
    $this->fakeRequest('printers/1234', 'printer_single_not_found');

    $printer = $this->printNode->printer(1234);

    expect($printer)->toBeNull();
});
