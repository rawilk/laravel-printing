<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Attributes\JobGroup;
use Rawilk\Printing\Api\Cups\Attributes\OperationGroup;
use Rawilk\Printing\Api\Cups\Attributes\PrinterGroup;
use Rawilk\Printing\Api\Cups\Attributes\UnsupportedGroup;

enum AttributeGroupTag: int
{
    case Reserved = 0x00;
    case OperationAttributes = 0x01;
    case JobAttributes = 0x02;
    case EndOfAttributes = 0x03;
    case PrinterAttributes = 0x04;
    case UnSupportedAttributes = 0x05;

    public static function getGroupClassByTag(int $tag): string
    {
        return match ($tag) {
            self::JobAttributes->value => JobGroup::class,
            self::OperationAttributes->value => OperationGroup::class,
            self::PrinterAttributes->value => PrinterGroup::class,
            self::UnSupportedAttributes->value => UnsupportedGroup::class,
        };
    }
}
