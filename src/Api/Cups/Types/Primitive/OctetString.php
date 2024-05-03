<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types\Primitive;

use Rawilk\Printing\Api\Cups\TypeTag;

class OctetString extends Text
{
    protected int $tag = TypeTag::OCTETSTRING->value;
}
