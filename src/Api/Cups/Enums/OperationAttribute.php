<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\Types\MimeMedia;
use Rawilk\Printing\Api\Cups\Types\NameWithoutLanguage;
use Rawilk\Printing\Api\Cups\Types\Primitive\Enum;
use Rawilk\Printing\Api\Cups\Types\Primitive\Integer;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Api\Cups\Types\RangeOfInteger;
use Rawilk\Printing\Api\Cups\Types\Uri;

enum OperationAttribute: string
{
    case Copies = 'copies';
    case DateTimeAtCreation = 'date-time-at-creation';
    case DocumentFormat = 'document-format';
    case JobName = 'job-name';
    case JobPrinterStateMessage = 'job-printer-state-message';
    case JobPrinterUri = 'job-printer-uri';
    case JobState = 'job-state';
    case JobUri = 'job-uri';
    case NumberOfDocuments = 'number-of-documents';
    case OrientationRequested = 'orientation-requested';
    case PageRanges = 'page-ranges';
    case PrinterUri = 'printer-uri';
    case RequestedAttributes = 'requested-attributes';
    case RequestingUserName = 'requesting-user-name';
    case Sides = 'sides';
    case WhichJobs = 'which-jobs';

    public function toKeyword(): Keyword
    {
        return new Keyword($this->value);
    }

    public function toType(mixed $value = null): Type
    {
        return match ($this) {
            self::PrinterUri, self::JobUri => new Uri($value),
            self::DocumentFormat => new MimeMedia($value),
            self::JobName => new NameWithoutLanguage($value),
            self::WhichJobs => new Keyword($value ?? 'not-completed'),
            self::OrientationRequested => new Enum($value ?? Orientation::Portrait->value),
            self::Copies => new Integer($value),
            self::PageRanges => new RangeOfInteger($value),
            self::RequestingUserName => new NameWithoutLanguage(iconv('UTF-8', 'ASCII//TRANSLIT', $value)),
            self::Sides => new Keyword($value),
            default => null,
        };
    }
}
