<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class Integer extends Type
{
    protected int $tag = TypeTag::INTEGER->value;

    public function encode(): string
    {
        return pack('n', strlen($this->value)) . pack('N', $this->value);
    }

    public static function fromBinary(string $binary, ?int $length = null): self
    {
        return new static(unpack('N', $binary)[1]);
    }
}
