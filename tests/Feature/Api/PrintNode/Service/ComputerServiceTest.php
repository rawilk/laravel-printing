<?php

declare(strict_types=1);

use Carbon\CarbonInterface;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Service\ComputerService;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $this->fakeRequests();

    $client = new PrintNodeClient(['api_key' => 'my-key']);
    $this->service = new ComputerService($client);
});

it('retrieves all computers', function () {
    $this->fakeRequest('computers');

    $response = $this->service->all();

    expect($response)->toHaveCount(3)
        ->toContainOnlyInstancesOf(Computer::class);
});

it('can limit results count', function () {
    $this->fakeRequest('computers_limit', expectation: function (Request $request) {
        expect($request->url())->toContain('limit=2');
    });

    $response = $this->service->all(['limit' => 2]);

    expect($response)->toHaveCount(2);
});

it('can retrieve a computer', function () {
    $this->fakeRequest('computer_single', expectation: function (Request $request) {
        expect($request->url())->toEndWith('/computers/14');
    });

    $computer = $this->service->retrieve(14);

    expect($computer)
        ->not->toBeNull()
        ->id->toBe(14)
        ->name->toBe('TUNGSTEN')
        ->inet->toBe('192.168.56.1')
        ->hostname->toBe('Pete@TUNGSTEN')
        ->state->toBe('disconnected')
        ->createdAt()->toBeInstanceOf(CarbonInterface::class)
        ->createdAt()->toBe(Date::parse('2015-11-17T16:06:24.644Z'));
});

test('retrieve returns null if no computer is found', function () {
    $this->fakeRequest('computer_single_not_found');

    $computer = $this->service->retrieve(1234);

    expect($computer)->toBeNull();
});

it('can retrieve a set of computers', function () {
    $this->fakeRequest('computer_set', expectation: function (Request $request) {
        expect($request->url())->toContain('/computers/12,13');
    });

    $response = $this->service->retrieveSet([12, 13]);

    expect($response)->toHaveCount(2);
});

test('retrieveSet() requires at least one id', function () {
    $this->service->retrieveSet([]);
})->throws(InvalidArgument::class, 'At least one computer ID must be provided for this request.');

it('can delete a computer', function () {
    $this->fakeRequest(
        callback: fn () => [14],
        expectation: function (Request $request) {
            expect($request->method())->toBe('DELETE')
                ->and($request->url())->toEndWith('/computers/14');
        },
    );

    $response = $this->service->delete(14);

    expect($response)->toBeArray()
        ->toEqualCanonicalizing([14]);
});

it('can delete all computers', function () {
    $this->fakeRequest(
        callback: fn () => [1, 2],
        expectation: function (Request $request) {
            expect($request->method())->toBe('DELETE');
        },
    );

    $response = $this->service->deleteMany();

    expect($response)->toBeArray()
        ->toEqualCanonicalizing([1, 2]);
});

it('can delete a set of computers', function () {
    $this->fakeRequest(
        callback: fn () => [1, 2, 3],
        expectation: function (Request $request) {
            expect($request->url())->toContain('/computers/1,2,3');
        },
    );

    $response = $this->service->deleteMany([1, 2, 3]);

    expect($response)->toEqualCanonicalizing([
        1, 2, 3,
    ]);
});

it('can retrieve all printers for a given computer', function () {
    $this->fakeRequest('printers', expectation: function (Request $request) {
        expect($request->url())
            ->toContain('/computers/1/printers')
            ->toContain('dir=asc');
    });

    $response = $this->service->printers(1, ['dir' => 'asc']);

    expect($response)->toHaveCount(24);
});

it('can retrieve a specific printer', function () {
    $this->fakeRequest('printer_single', expectation: function (Request $request) {
        expect($request->url())->toContain('/computers/1/printers/2');
    });

    $printer = $this->service->printer(1, 2);

    expect($printer)->toBeInstanceOf(Printer::class);
});

it('can retrieve a set of printers', function () {
    $this->fakeRequest('printers', expectation: function (Request $request) {
        expect($request->url())->toContain('/computers/1/printers/1,2');
    });

    $response = $this->service->printer(1, [1, 2]);

    expect($response)
        ->toBeInstanceOf(Collection::class)
        ->not->toBeEmpty();
});
