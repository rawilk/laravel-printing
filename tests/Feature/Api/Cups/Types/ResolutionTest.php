<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Resolution;

it('can be instantiated with a value', function () {
    $resolution = new Resolution('300x600dpi');

    expect($resolution->value)->toBe('300x600dpi')
        ->and($resolution->getTag())->toBe(TypeTag::Resolution->value);
});

it('can encode its value', function () {
    $resolution = new Resolution('300x600dpi');

    $expected = pack('n', 9)  // Length (9 bytes)
        . pack('N', 300)      // First value
        . pack('N', 600)      // Second value
        . pack('c', 3);       // dpi unit

    expect($resolution->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';

    $binary = pack('n', strlen($name)) . $name
        . pack('n', 9)   // Length (9 bytes)
        . pack('N', 300) // First value
        . pack('N', 600) // Second value
        . pack('c', 3);  // dpi unit

    $offset = 0;
    [$attrName, $instance] = Resolution::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Resolution::class)
        ->and($instance->value)->toBe('300x600dpi');
});

it('serializes to json', function () {
    $resolution = new Resolution('300x600dpi');

    expect(json_encode($resolution))->toBe('"300x600dpi"');
});
