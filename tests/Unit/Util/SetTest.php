<?php

declare(strict_types=1);

use Rawilk\Printing\Util\Set;

it('initializes with given elements', function () {
    $set = new Set(['apple', 'banana', 'cherry']);

    expect($set->toArray())->toBe(['apple', 'banana', 'cherry']);
});

it('can check if an element is included', function () {
    $set = new Set(['apple', 'banana']);

    expect($set->includes('apple'))->toBeTrue()
        ->and($set->includes('banana'))->toBeTrue()
        ->and($set->includes('cherry'))->toBeFalse();
});

it('can add new elements', function () {
    $set = new Set(['apple']);

    $set->add('banana');
    $set->add('cherry');

    expect($set->toArray())->toBe(['apple', 'banana', 'cherry']);
});

it('does not add duplicate elements', function () {
    $set = new Set(['apple']);

    $set->add('apple'); // Adding the same element again

    expect($set->toArray())->toBe(['apple']);
});

it('can discard elements', function () {
    $set = new Set(['apple', 'banana', 'cherry']);

    $set->discard('banana');

    expect($set->toArray())->toBe(['apple', 'cherry']);
});

it('does nothing when discarding a non-existing element', function () {
    $set = new Set(['apple', 'banana']);

    $set->discard('cherry'); // Not in the set

    expect($set->toArray())->toBe(['apple', 'banana']);
});

it('provides an iterator', function () {
    $set = new Set(['apple', 'banana', 'cherry']);

    $elements = [];
    foreach ($set as $item) {
        $elements[] = $item;
    }

    expect($elements)->toBe(['apple', 'banana', 'cherry']);
});
