<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;

it('can be instantiated with a keyword value', function () {
    $keyword = new Keyword('print-job');

    expect($keyword->value)->toBe('print-job')
        ->and($keyword->getTag())->toBe(TypeTag::Keyword->value);
});

it('can encode its value', function () {
    $keyword = new Keyword('print-job');
    $expected = pack('n', strlen('print-job')) . pack('a' . strlen('print-job'), 'print-job');

    expect($keyword->encode())->toBe($expected);
});

it('can decode from binary', function () {
    $name = 'foo-bar';
    $value = 'print-job';

    $binary = pack('n', strlen($name)) . $name
        . pack('n', strlen($value)) . $value;

    $offset = 0;
    [$attrName, $instance] = Keyword::fromBinary($binary, $offset);

    expect($attrName)->toBe('foo-bar')
        ->and($instance)->toBeInstanceOf(Keyword::class)
        ->and($instance->value)->toBe('print-job');
});

it('serializes to json correctly', function () {
    $keyword = new Keyword('print-job');

    expect(json_encode($keyword))->toBe('"print-job"');
});
