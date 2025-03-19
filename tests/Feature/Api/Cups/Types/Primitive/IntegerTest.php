<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Integer;

it('can be instantiated with an integer value', function () {
    $integer = new Integer(100);

    expect($integer->value)->toBe(100)
        ->and($integer->getTag())->toBe(TypeTag::Integer->value);
});

it('can encode its value', function () {
    $integer = new Integer(100);
    $expected = pack('n', 4) . pack('N', 100); // Length 4, Value 100

    expect($integer->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $binary = pack('n', strlen($name)) . $name
        . pack('n', 4)    // Length 4
        . pack('N', 100); // Integer value 100

    $offset = 0;
    [$attrName, $instance] = Integer::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Integer::class)
        ->and($instance->value)->toBe(100);
});

it('serializes to json correctly', function () {
    $integer = new Integer(100);

    expect(json_encode($integer))->toBe('100');
});
