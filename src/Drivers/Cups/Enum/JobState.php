<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Enum;

enum JobState: int
{
    case PENDING = 0x03;
    case PENDING_HELD = 0x04;
    case PROCESSING = 0x05;
    case PROCESSING_STOPPED = 0x06;
    case CANCELLED = 0x07;
    case ABORTED = 0x08;
    case COMPLETED = 0x09;
}
