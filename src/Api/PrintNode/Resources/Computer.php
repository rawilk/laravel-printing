<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

/**
 * A `Computer` represents a device that has the PrintNode Client software installed
 * on it, and which has successfully connected to PrintNode. When the PrintNode
 * Client runs on a computer it automatically reports the existence of the
 * computer to the server. From then on the computer is recognized by
 * the API.
 *
 * @property-read int $id The computer's ID
 * @property-read string $createTimestamp Time and date the computer was first registered with PrintNode
 * @property-read string $name The computer's name
 * @property-read string $state Current state of the computer
 * @property-read string|null $hostname The computer's host name
 * @property-read string|null $inet The computer's ipv4 address
 * @property-read string|null $inet6 The computer's ipv6 address
 * @property-read string|null $jre Reserved
 * @property-read string|null $version The PrintNode software version that is run on the computer
 */
class Computer extends PrintNodeApiResource
{
    use ApiOperations\All;
    use ApiOperations\Delete;
    use ApiOperations\Retrieve;
    use Concerns\HasDateAttributes;

    public function createdAt(): ?CarbonInterface
    {
        return $this->parseDate($this->createTimestamp);
    }

    /**
     * Fetch all printers attached to the computer.
     *
     * @return Collection<int, \Rawilk\Printing\Api\PrintNode\Resources\Printer>
     */
    public function printers(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $url = $this->instanceUrl() . '/printers';

        return static::_requestPage($url, $params ?? [], $opts, expectedResource: Printer::class);
    }

    /**
     * Find a specific printer attached to the computer. Pass an array for `$id` to find a set of
     * printers.
     *
     * @return null|Printer|Collection<int, Printer>
     */
    public function findPrinter(
        int|array $id,
        ?array $params = null,
        null|array|RequestOptions $opts = null
    ): null|Printer|Collection {
        $path = is_array($id)
            ? static::buildPath('/printers/%s', ...$id)
            : static::buildPath('/printers/%s', $id);

        $url = $this->instanceUrl() . $path;

        $printers = static::_requestPage($url, $params ?? [], $opts, expectedResource: Printer::class);

        return is_array($id) ? $printers : $printers->first();
    }
}
