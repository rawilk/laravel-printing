<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Text;

it('can be instantiated with a value', function () {
    $text = new Text('hello world');

    expect($text->value)->toBe('hello world')
        ->and($text->getTag())->toBe(TypeTag::Text->value);
});

it('can encode its value', function () {
    $text = new Text('Hello');

    expect($text->encode())->toBe(pack('n', 5) . 'Hello');
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $value = 'Test';

    $binary = pack('n', strlen($name)) . $name . pack('n', strlen($value)) . $value;
    $offset = 0;

    [$attrName, $instance] = Text::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Text::class)
        ->and($instance->value)->toBe('Test');
});

it('serializes to json', function () {
    $text = new Text('Json Test');

    expect(json_encode($text))->toBe('"Json Test"');
});
