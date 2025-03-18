<?php

declare(strict_types=1);

use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;

beforeEach(function () {
    Http::preventStrayRequests();

    PrintNode::setApiKey('my-key');
});

test('computer data', function () {
    $computer = Computer::make(samplePrintNodeData('computer_single')[0]);

    expect($computer)
        ->id->toBe(14)
        ->name->toBe('TUNGSTEN')
        ->inet->toBe('192.168.56.1')
        ->createdAt()->toBe(Date::parse('2015-11-17T16:06:24.644Z'))
        ->state->toBe('disconnected');
});

test('class url', function () {
    expect(Computer::classUrl())->toBe('/computers');
});

test('resource url', function () {
    expect(Computer::resourceUrl(123))->toBe('/computers/123');
});

test('instance url', function () {
    $computer = new Computer(39);

    expect($computer->instanceUrl())->toBe('/computers/39');
});

it('can refresh itself from the api', function () {
    $computer = new Computer(14);

    expect($computer)->not->toHaveKey('name');

    Http::fake([
        '/computers/14' => Http::response(samplePrintNodeData('computer_single')),
    ]);

    $computer->refresh();

    expect($computer)->toHaveKey('name')
        ->name->toBe('TUNGSTEN');
});

it('can be retrieved from the api', function () {
    Http::fake([
        '/computers/14' => Http::response(samplePrintNodeData('computer_single')),
    ]);

    $computer = Computer::retrieve(14);

    expect($computer)->id->toBe(14);
});

test('all computers can be retrieved', function () {
    Http::fake([
        '/computers' => Http::response(samplePrintNodeData('computers')),
    ]);

    $computers = Computer::all();

    expect($computers)->toHaveCount(3)
        ->toContainOnlyInstancesOf(Computer::class)
        ->first()->id->toBe(12);
});

test('retrieve all with options', function () {
    Http::fake([
        '/computers*' => Http::response(samplePrintNodeData('computers_limit')),
    ]);

    $computers = Computer::all(['limit' => 2]);

    expect($computers)->toHaveCount(2);

    Http::assertSent(function (Request $request) {
        expect($request->url())->toContain('limit=2');

        return true;
    });
});

it('can delete itself', function () {
    $computer = new Computer(14);

    Http::fake([
        '/computers/14' => Http::response([14]),
    ]);

    $computer->delete();

    Http::assertSent(function (Request $request) {
        expect($request->method())->toBe('DELETE')
            ->and($request->url())->toContain('/computers/14');

        return true;
    });
});

it('can fetch its printers', function () {
    $computer = new Computer(14);

    Http::fake([
        '/computers/14/printers' => Http::response(samplePrintNodeData('printers')),
    ]);

    $printers = $computer->printers();

    expect($printers)->toHaveCount(24)
        ->toContainOnlyInstancesOf(Printer::class);
});

it('can find a specific printer', function () {
    $computer = new Computer(14);

    Http::fake([
        '/computers/14/printers/39' => Http::response(samplePrintNodeData('printer_single')),
    ]);

    $printer = $computer->findPrinter(39);

    expect($printer)->toBeInstanceOf(Printer::class)
        ->id->toBe(39);
});

it('can find a set of printers', function () {
    $computer = new Computer(14);

    Http::fake([
        '/computers/14/printers/34,36' => Http::response(samplePrintNodeData('printer_set')),
    ]);

    $printers = $computer->findPrinter([34, 36]);

    expect($printers)->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->toContainOnlyInstancesOf(Printer::class);
});
