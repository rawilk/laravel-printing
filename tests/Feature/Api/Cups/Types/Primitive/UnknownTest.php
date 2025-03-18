<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Unknown;

it('can be instantiated with a null value', function () {
    $unknown = new Unknown(null);

    expect($unknown->value)->toBeNull()
        ->and($unknown->getTag())->toBe(TypeTag::Unknown->value);
});

it('can encode its value', function () {
    $unknown = new Unknown(null);
    $expected = pack('n', 0); // Length 0 (Unknown Value)

    expect($unknown->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';

    $binary = pack('n', strlen($name)) . $name
        . pack('n', 0); // Unknown Value length

    $offset = 0;
    [$attrName, $instance] = Unknown::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Unknown::class)
        ->and($instance->value)->toBeNull();
});

it('serializes to json correctly', function () {
    $unknown = new Unknown(null);

    expect(json_encode($unknown))->toBe('null');
});
