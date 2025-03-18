<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Boolean;

it('can be instantiated with a value', function () {
    $bool = new Boolean(true);

    expect($bool->value)->toBeTrue()
        ->and($bool->getTag())->toBe(TypeTag::Boolean->value);
});

it('can encode its value', function () {
    $boolTrue = new Boolean(true);
    $boolFalse = new Boolean(false);

    $expectedTrue = pack('n', 1) . pack('c', 1);  // Length 1, Value 1
    $expectedFalse = pack('n', 1) . pack('c', 0); // Length 1, Value 0

    expect($boolTrue->encode())->toBe($expectedTrue)
        ->and($boolFalse->encode())->toBe($expectedFalse);
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $binaryTrue = pack('n', strlen($name)) . $name
        . pack('n', 1)  // Length 1
        . pack('c', 1); // Boolean true

    $binaryFalse = pack('n', strlen($name)) . $name
        . pack('n', 1)  // Length 1
        . pack('c', 0); // Boolean false

    $offset = 0;
    [$attrNameTrue, $instanceTrue] = Boolean::fromBinary($binaryTrue, $offset);

    $offset = 0;
    [$attrNameFalse, $instanceFalse] = Boolean::fromBinary($binaryFalse, $offset);

    expect($attrNameTrue)->toBe('foo-bar')
        ->and($instanceTrue)->toBeInstanceOf(Boolean::class)
        ->and($instanceTrue->value)->toBeTrue()
        ->and($attrNameFalse)->toBe('foo-bar')
        ->and($instanceFalse)->toBeInstanceOf(Boolean::class)
        ->and($instanceFalse->value)->toBeFalse();
});

it('serializes to json', function () {
    $boolTrue = new Boolean(true);
    $boolFalse = new Boolean(false);

    expect(json_encode($boolTrue))->toBe(json_encode(true))
        ->and(json_encode($boolFalse))->toBe(json_encode(false));
});
