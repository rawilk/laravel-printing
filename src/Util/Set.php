<?php

declare(strict_types=1);

namespace Rawilk\Printing\Util;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 */
class Set implements IteratorAggregate
{
    private array $_elements = [];

    /**
     * @param  array<int, string>  $members
     */
    public function __construct(array $members = [])
    {
        foreach ($members as $item) {
            $this->_elements[$item] = true;
        }
    }

    public function includes(string $element): bool
    {
        return isset($this->_elements[$element]);
    }

    public function add(string $element): void
    {
        $this->_elements[$element] = true;
    }

    public function discard(string $element): void
    {
        unset($this->_elements[$element]);
    }

    public function toArray(): array
    {
        return array_keys($this->_elements);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }
}
