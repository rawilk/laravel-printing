<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources\Concerns;

use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

trait HasDateAttributes
{
    protected function parseDate(?string $date, string $format = 'Y-m-d\TH:i:s.v\Z'): ?CarbonInterface
    {
        if (blank($date)) {
            return null;
        }

        return Date::createFromFormat($format, $date);
    }
}
