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

    public function encode(): string
    {
        preg_match('/(\d+)x(\d+)(.*)/', $this->value, $matches);
        $reverseMap = array_flip(static::$unitMap);

        return pack('n', 9) . pack('N', $matches[1])
            . pack('N', $matches[2])
            . pack('c', $reverseMap[$matches[3]]);
    }


    public static function decode(string $binary, ?int $length = null): mixed
    {
        $value = unpack('Np/Np2/cu', $binary);
        return $value['p'] . 'x' . $value['p2'] . static::$unitMap[$value['u']];
    }
}
