<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;

it('parses a string for options', function () {
    $opts = RequestOptions::parse('foo');

    expect($opts)
        ->apiKey->toBe('foo')
        ->headers->toBe([])
        ->apiBase->toBeNull();
});

it('can block using strings for options', function () {
    RequestOptions::parse('foo', strict: true);
})->throws(InvalidArgument::class, 'Do not pass a string for request options.');

it('can parse null for options', function () {
    $opts = RequestOptions::parse(null);

    expect($opts)
        ->apiKey->toBeNull()
        ->headers->toBe([])
        ->apiBase->toBeNull();
});

it('can parse an empty array for options', function () {
    $opts = RequestOptions::parse([]);

    expect($opts)
        ->apiKey->toBeNull()
        ->headers->toBe([])
        ->apiBase->toBeNull();
});

it('parses an array with an api key', function () {
    $opts = RequestOptions::parse([
        'api_key' => 'foo',
    ]);

    expect($opts)
        ->apiKey->toBe('foo')
        ->headers->toEqualCanonicalizing([])
        ->apiBase->toBeNull();
});

it('parses an array with an idempotency key', function () {
    $opts = RequestOptions::parse([
        'idempotency_key' => 'foo',
    ]);

    expect($opts)
        ->apiKey->toBeNull()
        ->headers->toEqualCanonicalizing(['X-Idempotency-Key' => 'foo'])
        ->apiBase->toBeNull();
});

it('parses an array with api key and idempotency key', function () {
    $opts = RequestOptions::parse([
        'api_key' => 'foo',
        'idempotency_key' => 'foo',
    ]);

    expect($opts)
        ->apiKey->toBe('foo')
        ->headers->toEqualCanonicalizing(['X-Idempotency-Key' => 'foo'])
        ->apiBase->toBeNull();
});

it('can parse an array with unexpected keys', function () {
    $opts = RequestOptions::parse([
        'api_key' => 'foo',
        'foo' => 'bar',
    ]);

    expect($opts)
        ->apiKey->toBe('foo')
        ->headers->toBe([])
        ->apiBase->toBeNull();
});

it('can guard against unexpected array option keys', function () {
    RequestOptions::parse([
        'api_key' => 'foo',
        'foo' => 'bar',
    ], strict: true);
})->throws(InvalidArgument::class, 'Got unexpected keys in options array: foo');

it('can parse an array with an api base', function () {
    $opts = RequestOptions::parse([
        'api_base' => 'https://example.com',
    ]);

    expect($opts)
        ->apiKey->toBeNull()
        ->headers->toBe([])
        ->apiBase->toBe('https://example.com');
});

it('can merge options', function () {
    $baseOpts = RequestOptions::parse([
        'api_key' => 'foo',
        'idempotency_key' => 'foo',
        'api_base' => 'https://example.com',
    ]);

    $opts = $baseOpts->merge([
        'api_base' => 'https://acme.com',
        'idempotency_key' => 'bar',
    ]);

    expect($opts)
        ->apiKey->toBe('foo')
        ->headers->toEqualCanonicalizing(['X-Idempotency-Key' => 'bar'])
        ->apiBase->toBe('https://acme.com');
});

it('redacts the api key in debug info', function () {
    $opts = RequestOptions::parse([
        'api_key' => 'my_key_1234',
    ]);

    $debugInfo = print_r($opts, return: true);
    expect($debugInfo)->toContain('[apiKey] => my_k*******');

    $opts = RequestOptions::parse([]);

    $debugInfo = print_r($opts, return: true);
    expect($debugInfo)->toContain("[apiKey] => \n");
});
