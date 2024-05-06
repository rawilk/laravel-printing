<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Enum;

enum PrinterState: int
{
    case IDLE = 0x03;
    case PROCESSING = 0x04;
    case STOPPED = 0x05;
}
