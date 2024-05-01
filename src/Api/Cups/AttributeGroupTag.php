<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Attributes\JobGroup;
use Rawilk\Printing\Api\Cups\Attributes\OperationGroup;
use Rawilk\Printing\Api\Cups\Attributes\PrinterGroup;
use Rawilk\Printing\Api\Cups\Attributes\UnsupportedGroup;

enum AttributeGroupTag: int
{
    case RESERVED = 0x00;
    case OPERATION_ATTRIBUTES = 0x01;
    case JOB_ATTRIBUTES = 0x02;
    case END_OF_ATTRIBUTES = 0x03;
    case PRINTER_ATTRIBUTES = 0x04;
    case UNSUPPORTED_ATTRIBUTES = 0x05;

    public static function getGroupClassByTag(int $tag): string
    {
        return match ($tag) {
            AttributeGroupTag::JOB_ATTRIBUTES->value => JobGroup::class,
            AttributeGroupTag::OPERATION_ATTRIBUTES->value => OperationGroup::class,
            AttributeGroupTag::PRINTER_ATTRIBUTES->value => PrinterGroup::class,
            AttributeGroupTag::UNSUPPORTED_ATTRIBUTES->value => UnsupportedGroup::class,
        };
    }
}
