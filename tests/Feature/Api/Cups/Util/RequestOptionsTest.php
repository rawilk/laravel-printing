<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;

it('can parse null for options', function () {
    $opts = RequestOptions::parse(null);

    expect($opts)
        ->ip->toBeNull()
        ->username->toBeNull()
        ->password->toBeNull()
        ->port->toBeNull()
        ->secure->toBeNull()
        ->headers->toBe([]);
});

it('can parse an empty array', function () {
    $opts = RequestOptions::parse([]);

    expect($opts)
        ->ip->toBeNull()
        ->username->toBeNull()
        ->password->toBeNull()
        ->port->toBeNull()
        ->secure->toBeNull()
        ->headers->toBe([]);
});

it('parses an array with ip address', function () {
    $opts = RequestOptions::parse([
        'ip' => '127.0.0.1',
    ]);

    expect($opts)
        ->ip->toBe('127.0.0.1')
        ->username->toBeNull()
        ->password->toBeNull()
        ->port->toBeNull()
        ->secure->toBeNull()
        ->headers->toBe([]);
});

it('parses an array with unexpected options', function () {
    $opts = RequestOptions::parse([
        'ip' => '127.0.0.1',
        'foo' => 'bar',
    ]);

    expect($opts)
        ->ip->toBe('127.0.0.1')
        ->username->toBeNull()
        ->password->toBeNull()
        ->port->toBeNull()
        ->secure->toBeNull()
        ->headers->toBe([]);
});

it('guards against unexpected array option keys', function () {
    RequestOptions::parse([
        'ip' => '127.0.0.1',
        'foo' => 'bar',
    ], strict: true);
})->throws(InvalidArgument::class, 'Got unexpected keys in options array: foo');

it('parses array options', function () {
    $opts = RequestOptions::parse([
        'ip' => '127.0.0.1',
        'username' => 'foo',
        'password' => 'bar',
        'port' => 1010,
        'secure' => true,
    ]);

    expect($opts)
        ->ip->toBe('127.0.0.1')
        ->username->toBe('foo')
        ->password->toBe('bar')
        ->port->toBe(1010)
        ->secure->toBeTrue()
        ->headers->toBe([]);
});

it('can merge options', function () {
    $baseOpts = RequestOptions::parse([
        'ip' => '127.0.0.1',
        'username' => 'foo',
    ]);

    $opts = $baseOpts->merge([
        'ip' => '127.0.0.2',
        'password' => 'bar',
    ]);

    expect($opts)
        ->ip->toBe('127.0.0.2')
        ->username->toBe('foo')
        ->password->toBe('bar');
});

it('redacts the password in debug info', function () {
    $opts = RequestOptions::parse(['password' => 'my_password_1234']);

    $debugInfo = print_r($opts, return: true);
    expect($debugInfo)->toContain('[password] => ****************');

    $opts = RequestOptions::parse([]);

    $debugInfo = print_r($opts, return: true);
    expect($debugInfo)->toContain("[password] => \n");
});
