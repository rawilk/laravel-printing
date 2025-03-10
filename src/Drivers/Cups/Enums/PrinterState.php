<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Enums;

enum PrinterState: int
{
    case Idle = 0x03;
    case Processing = 0x04;
    case Stopped = 0x05;
}
