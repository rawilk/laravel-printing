<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use JsonSerializable;

abstract class Type implements JsonSerializable
{
    public function __construct(public mixed $value)
    {
    }

    protected int $tag;

    abstract public static function fromBinary(string $binary, ?int $length = null): self;

    /**
     * Returns value length and value in binary
     */
    abstract public function encode(): string;

    public function getTag()
    {
        return $this->tag;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
