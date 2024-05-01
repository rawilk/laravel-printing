<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\TypeTag;

class Unknown extends Type
{
    protected int $tag = TypeTag::UNKNOWN->value;

    public function encode(): string
    {
        return pack('n', 0) . '';
    }

    public static function fromBinary(string $binary, ?int $length = null): self
    {
        return new static(null);
    }
}