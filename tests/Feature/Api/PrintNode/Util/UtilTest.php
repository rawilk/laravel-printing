<?php

declare(strict_types=1);

use Rawilk\Printing\Api\PrintNode\Resources\Printer;
use Rawilk\Printing\Api\PrintNode\Util\Util;

test('isList', function () {
    $list = [5, 'foo', []];
    expect(Util::isList($list))->toBeTrue();

    $notList = [5, 'foo', [], 'bar' => 'baz'];
    expect(Util::isList($notList))->toBeFalse();
});

test('convertToPrintNodeObject toArray() includes the ID', function () {
    $printer = Util::convertToPrintNodeObject([
        'id' => 100,
    ], null, expectedResource: Printer::class);

    expect($printer->toArray())->toHaveKey('id');
});

test('utf-8', function () {
    // UTF-8 string
    $str = "\xc3\xa9";
    expect(Util::utf8($str))->toBe($str);

    // Latin-1 string
    $str = "\xe9";
    expect(Util::utf8($str))->toBe("\xc3\xa9");

    // Not a string
    $value = true;
    expect(Util::utf8($value))->toBe($value);
});
