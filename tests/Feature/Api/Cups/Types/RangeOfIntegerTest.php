<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Exceptions\RangeOverlap;
use Rawilk\Printing\Api\Cups\Types\RangeOfInteger;

it('can be instantiated with a range', function () {
    $range = new RangeOfInteger([10, 20]);

    expect($range->value)->toEqualCanonicalizing([10, 20])
        ->and($range->getTag())->toBe(TypeTag::RangeOfInteger->value);
});

it('can encode its value', function () {
    $range = new RangeOfInteger([10, 20]);

    $expected = pack('n', 8) // Length (8 bytes: 2 * 4-byte integers)
        . pack('N', 10)      // Lower bound
        . pack('N', 20);     // Upper bound

    expect($range->encode())->toBe($expected);
});

it('throws when the range overlaps', function () {
    $ranges = [
        new RangeOfInteger([10, 20]),
        new RangeOfInteger([15, 25]), // Overlaps with the first
    ];

    RangeOfInteger::checkOverlaps($ranges);
})->throws(RangeOverlap::class, 'Range overlap is not allowed!');

it('allows non-overlapping ranges', function () {
    $ranges = [
        new RangeOfInteger([10, 20]),
        new RangeOfInteger([21, 30]),
    ];

    expect(RangeOfInteger::checkOverlaps($ranges))->toBeTrue();
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $lower = 10;
    $upper = 20;

    $binary = pack('n', strlen($name)) . $name
        . pack('n', 8)  // Length (8 bytes)
        . pack('N', $lower)
        . pack('N', $upper);

    $offset = 0;
    [$attrName, $instance] = RangeOfInteger::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(RangeOfInteger::class)
        ->and($instance->value)->toEqualCanonicalizing([10, 20]);
});

it('serializes to json', function () {
    $range = new RangeOfInteger([10, 20]);

    expect(json_encode($range))->toBe('[10,20]');
});
