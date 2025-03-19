<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Collection;
use Rawilk\Printing\Api\Cups\Types\Primitive\Text;

it('can be instantiated with members', function () {
    $members = [
        'key1' => new Text('value1'),
        'key2' => new Text('value2'),
    ];

    $collection = new Collection($members);

    expect($collection->value)->toEqualCanonicalizing($members)
        ->and($collection->getTag())->toBe(TypeTag::Collection->value);
});

it('can encode its value', function () {
    $members = [
        'key1' => new Text('value1'),
        'key2' => new Text('value2'),
    ];

    $collection = new Collection($members);

    $expected = pack('n', 0)              // Value length is 0
        . pack('c', TypeTag::Member->value)          // Member tag
        . pack('n', 0)                    // Member name length is 0
        . pack('n', strlen('key1')) . 'key1' // First key
        . $members['key1']->encode()
        . pack('c', TypeTag::Member->value)          // Member tag
        . pack('n', 0)                    // Member name length is 0
        . pack('n', strlen('key2')) . 'key2' // Second key
        . $members['key2']->encode()
        . pack('c', TypeTag::CollectionEnd->value)   // Collection end tag
        . pack('n', 0)                    // End tag name length
        . pack('n', 0);                   // End tag value length

    expect($collection->encode())->toBe($expected);
});

it('serializes to json', function () {
    $members = [
        'key1' => new Text('value1'),
        'key2' => new Text('value2'),
    ];

    $collection = new Collection($members);

    expect(json_encode($collection))->toBe('{"key1":"value1","key2":"value2"}');
});
