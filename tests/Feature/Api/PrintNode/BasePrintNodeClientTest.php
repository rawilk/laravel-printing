<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\BasePrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Exceptions\AuthenticationFailure;
use Rawilk\Printing\Api\PrintNode\Exceptions\RequestOptionsFoundInParams;
use Rawilk\Printing\Api\PrintNode\Resources\Computer;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $this->fakeRequests();
});

test('constructor allows no params', function () {
    $client = new BasePrintNodeClient;

    expect($client->getApiKey())->toBeNull();
});

test('constructor throws if config is unexpected type', function () {
    new BasePrintNodeClient(null);
})->throws(InvalidArgumentException::class, '$config must be a string or an array');

test('constructor throws if api key is empty', function () {
    new BasePrintNodeClient('');
})->throws(InvalidArgumentException::class, 'api_key cannot be an empty string');

test('constructor throws if api key contains whitespace', function () {
    new BasePrintNodeClient("my_key_1234\n");
})->throws(InvalidArgumentException::class, 'api_key cannot contain whitespace');

test('constructor throws if api key is unexpected type', function () {
    new BasePrintNodeClient(['api_key' => 1234]);
})->throws(InvalidArgumentException::class, 'api_key must be null or a string');

test('constructor throws if config array contains unexpected key', function () {
    new BasePrintNodeClient(['foo' => 'bar', 'bar' => 'foo']);
})->throws(InvalidArgumentException::class, "Found unknown key(s) in configuration array: 'foo', 'bar'");

test('request with client api key', function () {
    $this->fakeRequest('printer_single');

    $client = new BasePrintNodeClient(['api_key' => 'my-key']);

    $printer = $client->request('get', '/printers/1', expectedResource: Printer::class)[0] ?? null;

    expect($printer)->toBeInstanceOf(Printer::class)
        ->and(invade($printer)->_opts->apiKey)->toBe('my-key');
});

test('request with api set in opts', function () {
    $this->fakeRequest('printer_single');

    $client = new BasePrintNodeClient;

    $printer = $client->request('get', '/printers/1', opts: ['api_key' => 'opts-key'], expectedResource: Printer::class)[0] ?? null;

    expect($printer)->toBeInstanceOf(Printer::class)
        ->and(invade($printer)->_opts->apiKey)->toBe('opts-key');
});

test('request throws if no api key set', function () {
    $this->fakeRequest('printer_single');

    $client = new BasePrintNodeClient;

    $client->request('get', '/printers/1');
})->throws(AuthenticationFailure::class, 'No API key provided.');

test('request throws if opts is array with unexpected keys', function () {
    $this->fakeRequest('printer_single');

    $client = new BasePrintNodeClient;

    $client->request('get', '/printers/1', opts: ['foo' => 'bar']);
})->throws(InvalidArgument::class, 'Got unexpected keys in options array: foo');

test('requestCollection with client api key', function () {
    $this->fakeRequest('computers');

    $client = new BasePrintNodeClient(['api_key' => 'client-key']);

    $computers = $client->requestCollection('get', '/computers', expectedResource: Computer::class);

    expect($computers)->not->toBeEmpty()
        ->and(invade($computers->first())->_opts->apiKey)->toBe('client-key');
});

test('request throws if option keys found in params', function () {
    $this->fakeRequest('printer_single');

    $client = new BasePrintNodeClient(['api_key' => 'my-key']);

    $client->request('get', '/printers/1', params: ['api_key' => 'other-key', 'api_base' => 'foo']);
})->throws(RequestOptionsFoundInParams::class, 'Options found in $params: api_key, api_base.');
