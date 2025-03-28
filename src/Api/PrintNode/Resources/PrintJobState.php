<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources;

use Carbon\CarbonInterface;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;

/**
 * A `PrintJobState` represents a state that a `PrintJob` was in at a given time
 * in the PrintNode API.
 *
 * @property-read int $printJobId The ID of the print job the state is for
 * @property-read string $state The state code for the print job
 * @property-read string $message Additional information about the state
 * @property-read string $clientVersion If the state was generated by a PrintNode Client, this is the Client's version; otherwise `null`
 * @property-read string $createTimestamp If the state was generated by the PrintNode Client, this is the timestamp at which the state
 *      was reported to the PrintNode Server. Otherwise, it is the timestamp at which the PrintNode Server generated the state.
 *
 * @proeprty-read int $age The time elapsed, in milliseconds, between the state's `$createTimestamp` and the `$createTimestamp` of the
 *      print job's `new` state status.
 */
class PrintJobState extends PrintNodeApiResource
{
    use ApiOperations\All;
    use Concerns\HasDateAttributes;

    public static function classUrl(): string
    {
        return '/printjobs/states';
    }

    public function createdAt(): ?CarbonInterface
    {
        return $this->parseDate($this->createTimestamp);
    }
}
