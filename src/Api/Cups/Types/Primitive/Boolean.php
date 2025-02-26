<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class Boolean extends Type
{
    protected int $tag = TypeTag::BOOLEAN->value;

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);

        $valueLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        $value = (bool) unpack('c', $binary, $offset)[1];
        $offset += $valueLen;

        return [$attrName, new static($value)];
    }

    public function encode(): string
    {
        return pack('n', 1) . pack('c', intval($this->value));
    }
}
