<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Attributes;

use Rawilk\Printing\Api\Cups\AttributeGroup;
use Rawilk\Printing\Api\Cups\AttributeGroupTag;

class JobGroup extends AttributeGroup
{
    protected int $tag = AttributeGroupTag::JOB_ATTRIBUTES->value;
}
