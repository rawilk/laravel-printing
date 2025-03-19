<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\BaseCupsClient;
use Rawilk\Printing\Api\Cups\Cups;

test('constructor allows no params', function () {
    $client = new BaseCupsClient;

    expect($client)
        ->getIp()->toBeNull()
        ->getAuth()->toEqualCanonicalizing([null, null])
        ->getPort()->toBe(Cups::DEFAULT_PORT)
        ->getSecure()->toBe(Cups::DEFAULT_SECURE);
});

test('constructor throws if ip is empty', function () {
    new BaseCupsClient(['ip' => '']);
})->throws(InvalidArgumentException::class, 'cups server ip cannot be an empty string');

test('constructor throws if ip contains whitespace', function () {
    new BaseCupsClient(['ip' => "127.0.0.1\n"]);
})->throws(InvalidArgumentException::class, 'cups server ip cannot contain whitespace');

test('constructor throws if ip is unexpected type', function () {
    new BaseCupsClient(['ip' => 1234]);
})->throws(InvalidArgumentException::class, 'cups server ip must be null or a string');

test('constructor throws if config array contains unexpected key', function () {
    new BaseCupsClient(['foo' => 'bar', 'bar' => 'foo']);
})->throws(InvalidArgumentException::class, "Found unknown key(s) in configuration array: 'foo', 'bar'");
