<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Type;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc3382#section-7.2
 */
class Collection extends Type
{
    protected int $tag = TypeTag::Collection->value;

    // Collection has an end tag
    protected int $endTag = TypeTag::CollectionEnd->value;

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);
        $offset += 2; // Value length

        $members = [];
        while (unpack('ctag', $binary, $offset)['tag'] === TypeTag::Member->value) {
            $nextTag = (unpack('ctag', $binary, $offset))['tag'];
            $offset++;

            $type = TypeTag::tryFrom($nextTag);
            $typeClass = $type->getClass();

            [$name, $value] = $typeClass::fromBinary($binary, $offset);
            $members[$name] = $value;
        }

        // Collection end tags
        $offset++; // 0x37
        $offset += 4; // Name, value length

        return [$attrName, new static($members)];
    }

    public function encode(): string
    {
        $binary = pack('n', 0); // Value length is 0

        foreach ($this->value as $key => $value) {
            $binary .= pack('c', TypeTag::Member->value);
            $binary .= pack('n', 0); // Member name length is 0

            $binary .= pack('n', strlen($key));
            $binary .= pack('a' . strlen($key), $key);

            $binary .= $value->encode();
        }

        // Collection has an end tag (with name, value)
        $binary .= pack('c', $this->endTag);
        $binary .= pack('n', 0); // End tag name length is 0
        $binary .= pack('n', 0); // End tag value length is 0

        return $binary;
    }
}
