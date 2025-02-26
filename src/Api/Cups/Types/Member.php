<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class Member extends Type
{
    protected int $tag = TypeTag::MEMBER->value;

    /**
     * @see https://datatracker.ietf.org/doc/html/rfc3382#section-7.2
     */
    public static function fromBinary(string $binary, int &$offset): array
    {
        // Name is empty
        self::nameFromBinary($binary, $offset);

        $valueLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        // This will be the attribute name
        $value = unpack('a' . $valueLen, $binary, $offset)[1];
        $offset += $valueLen;

        $nextTag = (unpack('ctag', $binary, $offset))['tag'];
        $offset++;

        $type = TypeTag::tryFrom($nextTag);
        $typeClass = $type->getClass();

        // This will be the value
        $value2 = $typeClass::fromBinary($binary, $offset)[1];

        return [$value, new static($value2)];
    }

    public function encode(): string
    {
        $binary = pack('c', $this->value->getTag());
        $binary .= pack('n', 0); // Name length is 0
        $binary .= $this->value->encode();

        return $binary;
    }
}
