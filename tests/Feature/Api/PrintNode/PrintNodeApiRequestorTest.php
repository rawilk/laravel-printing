<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use Rawilk\Printing\Api\PrintNode\Exceptions\AuthenticationFailure;
use Rawilk\Printing\Api\PrintNode\Exceptions\PrintNodeApiRequestFailed;
use Rawilk\Printing\Api\PrintNode\PrintNode;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiRequestor;
use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Tests\Feature\Api\PrintNode\FakesPrintNodeRequests;

uses(FakesPrintNodeRequests::class);

beforeEach(function () {
    Http::preventStrayRequests();

    $this->fakeRequests();
});

test('default headers', function () {
    $reflection = new ReflectionClass($requestor = new PrintNodeApiRequestor);
    $method = $reflection->getMethod('defaultHeaders');
    $method->setAccessible(true);

    $apiKey = 'my-test-api-key';

    $headers = $method->invoke($requestor, $apiKey);

    expect($headers)->toHaveKeys(['Authorization'])
        ->and($headers['Authorization'])->toBe('Basic ' . base64_encode($apiKey . ':'));
});

test('encode objects', function (mixed $value, mixed $expected) {
    $reflection = new ReflectionClass(PrintNodeApiRequestor::class);
    $method = $reflection->getMethod('encodeObjects');
    $method->setAccessible(true);

    $encoded = $method->invoke(null, $value);

    if (is_array($expected)) {
        expect($encoded)->toEqualCanonicalizing($expected);
    } else {
        expect($encoded)->toBe($expected);
    }
})->with([
    'resource' => fn () => [
        'value' => ['printer' => new Printer(401)],
        'expected' => ['printer' => 401],
    ],
    'preserves utf-8' => fn () => [
        'value' => ['printer' => '☃'],
        'expected' => ['printer' => '☃'],
    ],
    'encodes latin-1 -> utf-8' => fn () => [
        'value' => ['printer' => "\xe9"],
        'expected' => ['printer' => "\xc3\xa9"],
    ],
    'boolean true' => fn () => [
        'value' => true,
        'expected' => true,
    ],
    'string boolean true' => fn () => [
        'value' => 'true',
        'expected' => true,
    ],
    'boolean false' => fn () => [
        'value' => false,
        'expected' => false,
    ],
    'string boolean false' => fn () => [
        'value' => 'false',
        'expected' => false,
    ],
]);

it('throws error if no api key is set', function () {
    PrintNode::setApiKey(null);

    $requestor = new PrintNodeApiRequestor;

    $requestor->request('get', '/computers');
})->throws(AuthenticationFailure::class, 'No API key provided');

it('throws an error for bad requests', function () {
    $this->fakeRequest('whoami_bad_api_key', code: 401);

    $requestor = new PrintNodeApiRequestor('my-key');

    $requestor->request('get', '/whoami');
})->throws(PrintNodeApiRequestFailed::class, 'API Key not found', 401);

it('checks for null bytes in resource urls', function () {
    $requestor = new PrintNodeApiRequestor('my-key');

    $requestor->request('get', "/printers/123\0");
})->throws(InvalidArgument::class, 'null byte');
