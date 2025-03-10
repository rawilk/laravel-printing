<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\Resources\Whoami;

beforeEach(function () {
    Http::preventStrayRequests();
    PrintNode::setApiKey('my-key');
});

test('whoami data', function () {
    $whoami = Whoami::make(samplePrintNodeData('whoami'));

    expect($whoami)
        ->id->toBe(433)
        ->firstname->toBe('Peter')
        ->lastname->toBe('Tuthill')
        ->credits->toBe(10134)
        ->totalPrints->toBe(110)
        ->Tags->toBe([])
        ->state->toBe('active')
        ->isActive()->toBeTrue();
});

test('class url', function () {
    expect(Whoami::classUrl())->toBe('/whoami');
});

test('resource url', function () {
    expect(Whoami::resourceUrl())->toBe('/whoami');
});

test('instance url', function () {
    $whoami = new Whoami;

    expect($whoami->instanceUrl())->toBe('/whoami');
});

it('can refresh itself from the api', function () {
    $whoami = new Whoami;

    expect($whoami)->not->toHaveKey('id');

    Http::fake([
        '/whoami' => Http::response(samplePrintNodeData('whoami')),
    ]);

    $whoami->refresh();

    expect($whoami)->toHaveKey('id')
        ->id->toBe(433);
});
