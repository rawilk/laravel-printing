<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Types\Primitive\Text;

class Charset extends Text
{
    protected int $tag = TypeTag::Charset->value;
}
