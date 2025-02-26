<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Exceptions\UnknownType;
use Rawilk\Printing\Api\Cups\Types\Charset;
use Rawilk\Printing\Api\Cups\Types\Collection;
use Rawilk\Printing\Api\Cups\Types\DateTime;
use Rawilk\Printing\Api\Cups\Types\Member;
use Rawilk\Printing\Api\Cups\Types\MimeMedia;
use Rawilk\Printing\Api\Cups\Types\NameWithoutLanguage;
use Rawilk\Printing\Api\Cups\Types\NaturalLanguage;
use Rawilk\Printing\Api\Cups\Types\Primitive\Boolean;
use Rawilk\Printing\Api\Cups\Types\Primitive\Enum;
use Rawilk\Printing\Api\Cups\Types\Primitive\Integer;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Api\Cups\Types\Primitive\NoValue;
use Rawilk\Printing\Api\Cups\Types\Primitive\OctetString;
use Rawilk\Printing\Api\Cups\Types\Primitive\Unknown;
use Rawilk\Printing\Api\Cups\Types\RangeOfInteger;
use Rawilk\Printing\Api\Cups\Types\Resolution;
use Rawilk\Printing\Api\Cups\Types\TextWithoutLanguage;
use Rawilk\Printing\Api\Cups\Types\Uri;

enum TypeTag: int
{
    case UNSUPPORTED = 0x10;
    case UNKNOWN = 0x12;
    case NOVALUE = 0x13;
    case BOOLEAN = 0x22;
    case INTEGER = 0x21;
    case ENUM = 0x23;
    case OCTETSTRING = 0x30;
    case DATETIME = 0x31;
    case RESOLUTION = 0x32;
    case RANGEOFINTEGER = 0x33;
    case COLLECTION = 0x34;
    case TEXTWITHLANGUAGE = 0x35;
    case NAMEWITHLANGUAGE = 0x36;
    case COLLECTION_END = 0x37;
    case TEXTWITHOUTLANGUAGE = 0x41;
    case NAMEWITHOUTLANGUAGE = 0x42;
    case KEYWORD = 0x44;
    case URI = 0x45;
    case URISCHEME = 0x46;
    case CHARSET = 0x47;
    case NATURALLANGUAGE = 0x48;
    case MIMEMEDIATYPE = 0x49;
    case MEMBER = 0x4A;
    case NAME = 0x0008;
    case STATUSCODE = 0x000D;
    case TEXT = 0x000E;

    public function getClass(): string
    {
        return match ($this->value) {
            self::CHARSET->value => Charset::class,
            self::NATURALLANGUAGE->value => NaturalLanguage::class,
            self::OCTETSTRING->value => OctetString::class,
            self::INTEGER->value => Integer::class,
            self::DATETIME->value => DateTime::class,
            self::NOVALUE->value => NoValue::class,
            self::NAMEWITHOUTLANGUAGE->value => NameWithoutLanguage::class,
            self::URI->value => Uri::class,
            self::BOOLEAN->value => Boolean::class,
            self::ENUM->value => Enum::class,
            self::TEXTWITHOUTLANGUAGE->value => TextWithoutLanguage::class,
            self::KEYWORD->value => Keyword::class,
            self::UNKNOWN->value => Unknown::class,
            self::MIMEMEDIATYPE->value => MimeMedia::class,
            self::RESOLUTION->value => Resolution::class,
            self::RANGEOFINTEGER->value => RangeOfInteger::class,
            self::COLLECTION->value => Collection::class,
            self::MEMBER->value => Member::class,
            default => throw new UnknownType('Unknown type')
        };
    }
}
