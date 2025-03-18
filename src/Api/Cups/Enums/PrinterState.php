<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups\Enums;

enum PrinterState: int
{
    /**
     * The printer is ready and waiting for jobs but is not currently processing anything.
     */
    case Idle = 0x03;

    /**
     * The printer is actively processing a job.
     */
    case Processing = 0x04;

    /**
     * The printer is stopped due to an error, manual intervention, or administrative action.
     */
    case Stopped = 0x05;

    /**
     * Indicates if the status is likely online.
     */
    public function isOnline(): bool
    {
        return $this !== self::Stopped;
    }
}
