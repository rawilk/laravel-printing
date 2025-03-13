<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\CupsObject;
use Rawilk\Printing\Exceptions\InvalidArgument;

test('array access', function () {
    $obj = new CupsObject;

    $obj['foo'] = 'a';

    expect(isset($obj['foo']))->toBeTrue()
        ->and($obj['foo'])->toBe('a');

    unset($obj['foo']);

    expect(isset($obj['foo']))->toBeFalse();
});

test('property accessors', function () {
    $obj = new CupsObject;

    $obj->foo = 'a';

    expect(isset($obj->foo))->toBeTrue()
        ->and($obj->foo)->toBe('a');

    $obj->foo = null;

    expect(isset($obj->foo))->toBeFalse();
});

test('array accessors match property accessors', function () {
    $obj = new CupsObject;

    $obj->foo = 'a';
    expect($obj['foo'])->toBe('a');

    $obj['bar'] = 'b';
    expect($obj->bar)->toBe('b');
});

test('_values key count', function () {
    $obj = new CupsObject;

    expect($obj)->toHaveCount(0);

    $obj['key1'] = 'value1';
    expect($obj)->toHaveCount(1);

    $obj['key2'] = 'value2';
    expect($obj)->toHaveCount(2);

    unset($obj['key1']);
    expect($obj)->toHaveCount(1);
});

test('_values keys', function () {
    $obj = new CupsObject;
    $obj->foo = 'bar';

    expect($obj->keys())->toEqualCanonicalizing(['foo']);
});

test('_values values', function () {
    $obj = new CupsObject;
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

    $obj = CupsObject::make($array);

    $converted = $obj->toArray();

    expect($converted)->toBeArray()
        ->toEqualCanonicalizing($array);
});

test('non-existent property', function () {
    $obj = new CupsObject;

    expect($obj->nonexist)->toBeNull()
        ->and($obj['does-not-exist'])->toBeNull();
});

it('can be json encoded', function () {
    $obj = new CupsObject;
    $obj->foo = 'a';

    expect(json_encode($obj))->toBe('{"foo":"a"}');
});

it('can be converted to a string', function () {
    $obj = new CupsObject;
    $obj->foo = 'a';

    $expected = <<<'STR'
    Rawilk\Printing\Api\Cups\CupsObject JSON: {
        "foo": "a"
    }
    STR;

    expect((string) $obj)->toBe($expected);
});

test('update nested attribute', function () {
    $obj = new CupsObject;

    $obj->metadata = ['bar'];
    expect($obj->metadata)->toEqualCanonicalizing(['bar']);

    $obj->metadata = ['baz', 'qux'];
    expect($obj->metadata)->toEqualCanonicalizing(['baz', 'qux']);
});

it('guards against setting permanent attributes', function () {
    $obj = new CupsObject;

    $obj->uri = 'foo';
})->throws(InvalidArgument::class);

test('uri can be passed to the constructor', function () {
    $obj = new CupsObject('foo');

    expect($obj)->uri->toBe('foo');
});

test('camelCase property setter converts to kebab-case', function () {
    $obj = new CupsObject;
    $obj->fooBar = 'a';

    expect(isset($obj['foo-bar']))->toBeTrue()
        ->and($obj['foo-bar'])->toBe('a');
});

test('camelCase property getter converts to kebab-case', function () {
    $obj = new CupsObject;
    $obj['foo-bar'] = 'a';

    expect(isset($obj->fooBar))->toBeTrue()
        ->and($obj->fooBar)->toBe('a');
});

test('array setter converts to kebab-case', function () {
    $obj = new CupsObject;
    $obj['fooBar'] = 'a';

    expect(isset($obj['foo-bar']))->toBeTrue();
});
