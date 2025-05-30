<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Exceptions\RangeOverlap;
use Rawilk\Printing\Api\Cups\Type;

class RangeOfInteger extends Type
{
    protected int $tag = TypeTag::RangeOfInteger->value;

    public static function fromBinary(string $binary, int &$offset): array
    {
        $attrName = self::nameFromBinary($binary, $offset);

        $valueLen = (unpack('n', $binary, $offset))[1];
        $offset += 2;

        $value = unpack('Nl/Nu', $binary, $offset);
        $offset += $valueLen;

        return [$attrName, new static([$value['l'], $value['u']])];
    }

    /**
     * Sorts and checks the array for overlaps
     *
     * @param  array<int, RangeOfInteger>  $values
     *
     * @throws RangeOverlap
     */
    public static function checkOverlaps(array &$values): bool
    {
        usort(
            $values,
            static function ($a, $b) {
                return $a->value[0] - $b->value[0];
            }
        );

        $count = count($values);
        for ($i = 0; $i < $count - 1; $i++) {
            if ($values[$i]->value[1] >= $values[$i + 1]->value[0]) {
                throw new RangeOverlap('Range overlap is not allowed!');
            }
        }

        return true; // No overlaps found
    }

    public function encode(): string
    {
        return pack('n', 8) . pack('N', $this->value[0]) . pack('N', $this->value[1]);
    }
}
