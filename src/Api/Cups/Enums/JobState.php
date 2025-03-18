<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum JobState: int
{
    case Pending = 0x03;
    case PendingHeld = 0x04;
    case Processing = 0x05;
    case ProcessingStopped = 0x06;
    case Cancelled = 0x07;
    case Aborted = 0x08;
    case Completed = 0x09;
}
