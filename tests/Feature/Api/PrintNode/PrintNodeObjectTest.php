<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\PrintNodeObject;
use Rawilk\Printing\Exceptions\InvalidArgument;

test('array access', function () {
    $obj = new PrintNodeObject;

    $obj['foo'] = 'a';

    expect(isset($obj['foo']))->toBeTrue()
        ->and($obj['foo'])->toBe('a');

    unset($obj['foo']);

    expect(isset($obj['foo']))->toBeFalse();
});

test('property accessors', function () {
    $obj = new PrintNodeObject;

    $obj->foo = 'a';

    expect(isset($obj->foo))->toBeTrue()
        ->and($obj->foo)->toBe('a');

    $obj->foo = null;

    expect(isset($obj->foo))->toBeFalse();
});

test('array accessors match property accessors', function () {
    $obj = new PrintNodeObject;

    $obj->foo = 'a';
    expect($obj['foo'])->toBe('a');

    $obj['bar'] = 'b';
    expect($obj->bar)->toBe('b');
});

test('_values key count', function () {
    $obj = new PrintNodeObject;

    expect($obj)->toHaveCount(0);

    $obj['key1'] = 'value1';
    expect($obj)->toHaveCount(1);

    $obj['key2'] = 'value2';
    expect($obj)->toHaveCount(2);

    unset($obj['key1']);
    expect($obj)->toHaveCount(1);
});

test('_values keys', function () {
    $obj = new PrintNodeObject;
    $obj->foo = 'bar';

    expect($obj->keys())->toEqualCanonicalizing(['foo']);
});

test('_values values', function () {
    $obj = new PrintNodeObject;
    $obj->foo = 'bar';

    expect($obj->values())->toEqualCanonicalizing(['bar']);
});

it('converts to array', function () {
    $array = [
        'foo' => 'a',
        'list' => [1, 2, 3],
        'null' => null,
        'metadata' => [
            'key' => 'value',
            1 => 'one',
        ],
    ];

    $obj = PrintNodeObject::make($array);

    $converted = $obj->toArray();

    expect($converted)->toBeArray()
        ->toEqualCanonicalizing($array);
});

it('converts nested objects to array', function () {
    // Deep nested associated array (when contained in an indexed array)
    // or PrintNodeObject
    $nestedArray = ['id' => 7, 'foo' => 'bar'];
    $nested = PrintNodeObject::make($nestedArray);

    $obj = PrintNodeObject::make([
        'id' => 1,
        'list' => [$nested],
    ]);

    $expected = [
        'id' => 1,
        'list' => [$nestedArray],
    ];

    expect($obj->toArray())
        ->toEqualCanonicalizing($expected);
});

test('non-existent property', function () {
    $obj = new PrintNodeObject;

    expect($obj->nonexist)->toBeNull()
        ->and($obj['does-not-exist'])->toBeNull();
});

it('can be json encoded', function () {
    $obj = new PrintNodeObject;
    $obj->foo = 'a';

    expect(json_encode($obj))->toBe('{"foo":"a"}');
});

it('can be converted to a string', function () {
    $obj = new PrintNodeObject;
    $obj->foo = 'a';

    $expected = <<<'STR'
    Rawilk\Printing\Api\PrintNode\PrintNodeObject JSON: {
        "foo": "a"
    }
    STR;

    expect((string) $obj)->toBe($expected);
});

test('update nested attribute', function () {
    $obj = new PrintNodeObject;

    $obj->metadata = ['bar'];
    expect($obj->metadata)->toEqualCanonicalizing(['bar']);

    $obj->metadata = ['baz', 'qux'];
    expect($obj->metadata)->toEqualCanonicalizing(['baz', 'qux']);
});

it('guards against setting permanent attributes', function () {
    $obj = new PrintNodeObject;

    $obj->id = 123;
})->throws(InvalidArgument::class);

test('id can be passed to constructor', function () {
    $obj = new PrintNodeObject(['id' => 123, 'other' => 'bar']);
    expect($obj->id)->toBe(123);

    $obj = new PrintNodeObject(555);
    expect($obj->id)->toBe(555);

    $obj = new PrintNodeObject('my-id');
    expect($obj->id)->toBe('my-id');
});
