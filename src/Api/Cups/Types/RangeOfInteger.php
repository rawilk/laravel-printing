<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class RangeOfInteger extends Type
{
    protected int $tag = TypeTag::RANGEOFINTEGER->value;

    /**
     * @param array<int, int[]>|int[] $value
     */
    public function __construct(public mixed $value)
    {
        parent::__construct($value);
        $this->checkOverlaps();
    }

    public function encode(): string
    {
        return pack('n', 8) . pack('N', $this->value[0]) .  pack('N', $this->value[1]);
    }

    public static function fromBinary(string $binary, ?int $length = null): self
    {
        $value = unpack('Nl/Nu', $binary);
        return new static([[$value['l'], $value['u']]]);
    }

    public function addRange($lower, $upper)
    {
        $this->value[] = [$lower, $upper];
        $this->checkOverlaps();
    }

    private function sortValues()
    {
        usort(
            $this->value,
            function ($a, $b) {
                return $a[0] - $b[0];
            }
        );
    }

    private function checkOverlaps()
    {
        if (gettype($this->value[0]) !== 'array') {
            return;
        }
        $this->sortValues();
        $ranges = $this->value;

        $count = count($ranges);
        for ($i = 0; $i < $count - 1; $i++) {
            if ($ranges[$i][1] >= $ranges[$i + 1][0]) {
                throw new \Rawilk\Printing\Api\Cups\Exceptions\RangeOverlap('Range overlap is not allowed!');
            }
        }
        return true; // No overlaps found
    }
}
