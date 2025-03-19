<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Member;
use Rawilk\Printing\Api\Cups\Types\Primitive\Text;

it('can be instantiated with a value', function () {
    $text = new Text('MemberValue');
    $member = new Member($text);

    expect($member->value)->toBe($text)
        ->and($member->getTag())->toBe(TypeTag::Member->value);
});

it('can encode its value', function () {
    $text = new Text('MemberValue');
    $member = new Member($text);

    $expected = pack('c', TypeTag::Text->value)
        . pack('n', 0) // Empty name length
        . $text->encode();

    expect($member->encode())->toBe($expected);
});

it('serializes to json', function () {
    $text = new Text('MemberValue');
    $member = new Member($text);

    expect(json_encode($member))->toBe('"MemberValue"');
});
