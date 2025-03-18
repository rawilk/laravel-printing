<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Enum;

it('can be instantiated with an enum value', function () {
    $enum = new Enum(42);

    expect($enum->value)->toBe(42)
        ->and($enum->getTag())->toBe(TypeTag::Enum->value);
});

it('can encode its value', function () {
    $enum = new Enum(42);
    $expected = pack('n', 4) . pack('N', 42); // Length 4, Value 42

    expect($enum->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $binary = pack('n', strlen($name)) . $name
        . pack('n', 4)  // Length 4
        . pack('N', 42); // Enum value 42

    $offset = 0;
    [$attrName, $instance] = Enum::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Enum::class)
        ->and($instance->value)->toBe(42);
});

it('serializes to json correctly', function () {
    $enum = new Enum(42);

    expect(json_encode($enum))->toBe('42');
});
