<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class NoValue extends Type
{
    protected int $tag = TypeTag::NOVALUE->value;

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);
        $offset += 2; // Value length

        return [$attrName, new static(null)];
    }

    public function encode(): string
    {
        return pack('n', 0) . '';
    }
}
