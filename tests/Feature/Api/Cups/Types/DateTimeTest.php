<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Date;
use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\DateTime as DateTimeType;

it('can be instantiated with a value', function () {
    $this->freezeSecond();

    $date = new DateTimeType(now());

    expect($date->value)->toBe(now())
        ->and($date->getTag())->toBe(TypeTag::DateTime->value);
});

it('can encode its value', function () {
    $date = Date::parse('2024-03-12 15:30:45', 'UTC');
    $type = new DateTimeType($date);

    $expected = pack('n', 11) // Length
        . pack('n', 2024)     // Year
        . pack('c', 3)        // Month
        . pack('c', 12)       // Day
        . pack('c', 15)       // Hour
        . pack('c', 30)       // Minute
        . pack('c', 45)       // Second
        . pack('c', 0)        // Reserved byte
        . pack('a', '+')      // UTC Symbol
        . pack('c', 0)        // UTC Hour Offset
        . pack('c', 0);       // UTC Minute Offset

    expect($type->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $date = Date::parse('2024-03-12 15:30:45', 'UTC');

    $binary = pack('n', strlen($name)) . $name
        . pack('n', 11)       // Length
        . pack('n', 2024)     // Year
        . pack('c', 3)        // Month
        . pack('c', 12)       // Day
        . pack('c', 15)       // Hour
        . pack('c', 30)       // Minute
        . pack('c', 45)       // Second
        . pack('c', 0)        // Reserved byte
        . pack('a', '+')      // UTC Symbol
        . pack('c', 0)        // UTC Hour Offset
        . pack('c', 0);       // UTC Minute Offset

    $offset = 0;

    [$attrName, $instance] = DateTimeType::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(DateTimeType::class)
        ->and($instance->value)->toBe($date);
});

it('serializes to json', function () {
    $date = Date::parse('2024-03-12 15:30:45', 'UTC');
    $type = new DateTimeType($date);

    expect(json_encode($type))->toBe('"2024-03-12T15:30:45.000000Z"');
});
