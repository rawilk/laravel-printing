<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum ContentType: string
{
    case OctetStream = 'application/octet-stream';
    case Pdf = 'application/pdf';
    case Postscript = 'application/postscript';
    case AdobeReaderPostscript = 'application/vnd.adobe-reader-postscript';
    case CupsPdf = 'application/vnd.cups-pdf';
    case CupsPdfBanner = 'application/vnd.cups-pdf-banner';
    case CupsPostscript = 'application/vnd.cups-postscript';
    case CupsRaster = 'application/vnd.cups-raster';
    case CupsRaw = 'application/vnd.cups-raw';
    case CShell = 'application/x-cshell';
    case CSource = 'application/x-csource';
    case Perl = 'application/x-perl';
    case Shell = 'application/x-shell';
    case Gif = 'image/gif';
    case Jpeg = 'image/jpeg';
    case Png = 'image/png';
    case PwgRaster = 'image/pwg-raster';
    case Tiff = 'image/tiff';
    case Urf = 'image/urf';
    case Bitmap = 'image/x-bitmap';
    case PhotoCd = 'image/x-photocd';
    case PortableAnymap = 'image/x-portable-anymap';
    case PortableBitmap = 'image/x-portable-bitmap';
    case PortableGraymap = 'image/x-portable-graymap';
    case PortablePixmap = 'image/x-portable-pixmap';
    case SgiRgb = 'image/x-sgi-rgb';
    case SunRaster = 'image/x-sun-raster';
    case XBitmap = 'image/x-xbitmap';
    case XXpiXmap = 'image/x-xpixmap';
    case XWindowDump = 'image/x-xwindowdump';
    case Css = 'text/css';
    case Html = 'text/html';
    case Plain = 'text/plain';
}
