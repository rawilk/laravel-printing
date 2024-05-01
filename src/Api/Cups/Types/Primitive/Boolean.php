<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class Boolean extends Type
{
    protected int $tag = TypeTag::BOOLEAN->value;

    public function encode(): string
    {
        return pack('n', 1) . pack('c', intval($this->value));
    }

    public static function fromBinary(string $binary, ?int $length = null): self
    {
        return new static((bool) unpack('c', $binary)[1]);
    }
}
