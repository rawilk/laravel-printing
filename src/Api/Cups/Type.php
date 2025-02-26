<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use JsonSerializable;

abstract class Type implements JsonSerializable
{
    protected int $tag;

    public function __construct(public mixed $value) {}

    /**
     * Returns attribute from binary and increments offset
     *
     * @return [string, Type]
     */
    abstract public static function fromBinary(string $binary, int &$offset): array;

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

    /**
     * Returns name from binary and increments offset
     *
     * @return string attribute name
     */
    protected static function nameFromBinary(string $binary, int &$offset): string
    {
        $nameLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        $attrName = unpack('a' . $nameLen, $binary, $offset)[1];
        $offset += $nameLen;

        return $attrName;
    }
}
