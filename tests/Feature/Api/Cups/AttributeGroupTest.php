<?php

declare(strict_types=1);

use Rawilk\Printing\Api\Cups\AttributeGroup;
use Rawilk\Printing\Api\Cups\Exceptions\TypeNotSpecified;
use Rawilk\Printing\Api\Cups\Type;

beforeEach(function () {
    $this->attributeGroup = new class extends AttributeGroup
    {
        protected int $tag = 0x01; // Example tag
    };
});

it('can encode single attributes', function () {
    $mockType = Mockery::mock(Type::class);
    $mockType->shouldReceive('getTag')->andReturn(0x21);
    $mockType->shouldReceive('encode')->andReturn(pack('n', 4) . 'test');

    $this->attributeGroup->test = $mockType;

    $encoded = $this->attributeGroup->encode();

    expect($encoded)->toBeString()->toStartWith(pack('c', 0x01));
});

it('can encode array attributes', function () {
    $mockType1 = Mockery::mock(Type::class);
    $mockType1->shouldReceive('getTag')->andReturn(0x22);
    $mockType1->shouldReceive('encode')->andReturn(pack('n', 4) . 'data');

    $mockType2 = Mockery::mock(Type::class);
    $mockType2->shouldReceive('getTag')->andReturn(0x22);
    $mockType2->shouldReceive('encode')->andReturn(pack('n', 4) . 'more');

    $this->attributeGroup->multi = [$mockType1, $mockType2];

    $encoded = $this->attributeGroup->encode();

    expect($encoded)->toBeString()->toStartWith(pack('c', 0x01));
});

it('supports array access', function () {
    $mockType = Mockery::mock(Type::class);
    $this->attributeGroup['test'] = $mockType;

    expect(isset($this->attributeGroup['test']))->toBeTrue()
        ->and($this->attributeGroup['test'])->toBe($mockType);

    unset($this->attributeGroup['test']);

    expect(isset($this->attributeGroup['test']))->toBeFalse();
});

it('serializes to array', function () {
    $mockType = Mockery::mock(Type::class);
    $this->attributeGroup->test = $mockType;

    expect($this->attributeGroup->toArray())->toHaveKey('test', $mockType);
});

it('serializes to json', function () {
    $mockType = Mockery::mock(Type::class);
    $mockType->shouldReceive('jsonSerialize')->once()->andReturn('foo');
    $this->attributeGroup->test = $mockType;

    expect(json_encode($this->attributeGroup))->toBe('{"test":"foo"}');
});

it('throws when encoding with non-type attributes set', function () {
    $this->attributeGroup->test = 'invalid_type';

    $this->attributeGroup->encode();
})->throws(TypeNotSpecified::class, 'Attribute value has to be of type ' . Type::class);
