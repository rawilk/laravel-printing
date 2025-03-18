<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\NoValue;

it('can be instantiated with a null value', function () {
    $noValue = new NoValue(null);

    expect($noValue->value)->toBeNull()
        ->and($noValue->getTag())->toBe(TypeTag::NoValue->value);
});

it('can encode its value', function () {
    $noValue = new NoValue(null);
    $expected = pack('n', 0); // Length 0 (No Value)

    expect($noValue->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';

    $binary = pack('n', strlen($name)) . $name
        . pack('n', 0); // No Value length

    $offset = 0;
    [$attrName, $instance] = NoValue::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(NoValue::class)
        ->and($instance->value)->toBeNull();
});

it('serializes to json correctly', function () {
    $noValue = new NoValue(null);

    expect(json_encode($noValue))->toBe('null');
});
