<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Attributes;

use Rawilk\Printing\Api\Cups\AttributeGroup;
use Rawilk\Printing\Api\Cups\Enums\AttributeGroupTag;

class OperationGroup extends AttributeGroup
{
    protected int $tag = AttributeGroupTag::OperationAttributes->value;
}
