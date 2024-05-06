<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Types;

use Rawilk\Printing\Api\Cups\Types\Primitive\Text;
use Rawilk\Printing\Api\Cups\TypeTag;

class NameWithoutLanguage extends Text
{
    protected int $tag = TypeTag::NAMEWITHOUTLANGUAGE->value;
}
