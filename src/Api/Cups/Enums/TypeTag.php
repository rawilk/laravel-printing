<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

use Rawilk\Printing\Api\Cups\Exceptions\UnknownType;
use Rawilk\Printing\Api\Cups\Types;

enum TypeTag: int
{
    case UnSupported = 0x10;
    case Unknown = 0x12;
    case NoValue = 0x13;
    case Boolean = 0x22;
    case Integer = 0x21;
    case Enum = 0x23;
    case OctetString = 0x30;
    case DateTime = 0x31;
    case Resolution = 0x32;
    case RangeOfInteger = 0x33;
    case Collection = 0x34;
    case TextWithLanguage = 0x35;
    case NameWithLanguage = 0x36;
    case CollectionEnd = 0x37;
    case TextWithoutLanguage = 0x41;
    case NameWithoutLanguage = 0x42;
    case Keyword = 0x44;
    case Uri = 0x45;
    case UriScheme = 0x46;
    case Charset = 0x47;
    case NaturalLanguage = 0x48;
    case MimeMediaType = 0x49;
    case Member = 0x4A;
    case Name = 0x0008;
    case StatusCode = 0x000D;
    case Text = 0x000E;

    public function getClass(): string
    {
        return match ($this) {
            self::Charset => Types\Charset::class,
            self::NaturalLanguage => Types\NaturalLanguage::class,
            self::OctetString => Types\Primitive\OctetString::class,
            self::Integer => Types\Primitive\Integer::class,
            self::DateTime => Types\DateTime::class,
            self::NoValue => Types\Primitive\NoValue::class,
            self::NameWithoutLanguage => Types\NameWithoutLanguage::class,
            self::Uri => Types\Uri::class,
            self::Boolean => Types\Primitive\Boolean::class,
            self::Enum => Types\Primitive\Enum::class,
            self::TextWithoutLanguage => Types\TextWithoutLanguage::class,
            self::Keyword => Types\Primitive\Keyword::class,
            self::Unknown => Types\Primitive\Unknown::class,
            self::MimeMediaType => Types\MimeMedia::class,
            self::Resolution => Types\Resolution::class,
            self::RangeOfInteger => Types\RangeOfInteger::class,
            self::Collection => Types\Collection::class,
            self::Member => Types\Member::class,
            default => throw new UnknownType('Unknown type')
        };
    }
}
