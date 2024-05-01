<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class OctetString extends Type
{
    protected int $tag = TypeTag::OCTETSTRING->value;

    public function encode(): string
    {
        return pack('a', strlen($this->value)) . pack('a' . strlen($this->value), $this->value);
    }

    public static function fromBinary(string $binary, ?int $length = null): self
    {
        return new static(unpack('a' . $length, $binary)[1]);
    }
}
