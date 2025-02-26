<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Types\Primitive\Text;
use Rawilk\Printing\Api\Cups\TypeTag;

class Resolution extends Text
{
    protected int $tag = TypeTag::RESOLUTION->value;

    private static $unitMap = [
        3 => 'dpi',
        4 => 'dpc',
    ];

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);

        $valueLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        $value = unpack('Np/Np2/cu', $binary, $offset);
        $offset += $valueLen;

        return [$attrName, new static($value['p'] . 'x' . $value['p2'] . static::$unitMap[$value['u']])];
    }

    public function encode(): string
    {
        preg_match('/(\d+)x(\d+)(.*)/', $this->value, $matches);
        $reverseMap = array_flip(static::$unitMap);

        return pack('n', 9) . pack('N', $matches[1])
            . pack('N', $matches[2])
            . pack('c', $reverseMap[$matches[3]]);
    }
}
