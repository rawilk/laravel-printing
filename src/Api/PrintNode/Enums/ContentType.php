<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Enums;

enum ContentType: string
{
    case PdfBase64 = 'pdf_base64';
    case PdfUri = 'pdf_uri';
    case RawBase64 = 'raw_base64';
    case RawUri = 'raw_uri';
}
