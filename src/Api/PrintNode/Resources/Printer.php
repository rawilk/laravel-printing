<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;
use Rawilk\Printing\Api\PrintNode\Resources\Support\PrinterCapabilities;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;

/**
 * A `Printer` represents a Printer attached to a `Computer` object in
 * the PrintNode API.
 *
 * @property-read int $id The printer's ID
 * @property-read string $createTimestamp Time and date the printer was first registered with PrintNode
 * @property-read \Rawilk\Printing\Api\PrintNode\Resources\Computer $computer The computer object the printer is attached to
 * @property-read string $name The name of the printer
 * @property-read string|null $description The description of the printer reported by the client
 * @property-read null|PrinterCapabilities $capabilities The capabilities of the printer reported by the client
 * @property-read bool $default Flag that indicates if this is the default printer for this computer
 * @property-read string $state The state of the printer reported by the client
 */
class Printer extends PrintNodeApiResource
{
    use ApiOperations\All;
    use ApiOperations\Retrieve;
    use Concerns\HasDateAttributes;

    public function createdAt(): ?CarbonInterface
    {
        return $this->parseDate($this->createTimestamp);
    }

    public function copies(): int
    {
        return $this->capabilities?->copies ?? 1;
    }

    public function isColor(): bool
    {
        return $this->capabilities?->color === true;
    }

    public function canCollate(): bool
    {
        return $this->capabilities?->collate ?? false;
    }

    public function media(): array
    {
        return $this->capabilities?->medias ?? [];
    }

    public function bins(): array
    {
        return $this->capabilities?->bins ?? [];
    }

    // Alias for bins()
    public function trays(): array
    {
        return $this->bins();
    }

    public function isOnline(): bool
    {
        return strtolower($this->state) === 'online';
    }

    /**
     * Fetch all print jobs that have been sent to the printer.
     *
     * @return Collection<int, PrintJob>
     */
    public function printJobs(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $url = $this->instanceUrl() . '/printjobs';

        return static::_requestPage($url, $params ?? [], $opts, expectedResource: PrintJob::class);
    }

    /**
     * Find a specific job that was sent to the printer. Pass an array for `$id` to find a set
     * of jobs.
     *
     * @return null|PrintJob|Collection<int, PrintJob>
     */
    public function findPrintJob(
        int|array $id,
        ?array $params = null,
        null|array|RequestOptions $opts = null
    ): null|PrintJob|Collection {
        $path = is_array($id)
            ? static::buildPath('/printjobs/%s', ...$id)
            : static::buildPath('/printjobs/%s', $id);

        $url = $this->instanceUrl() . $path;

        $jobs = static::_requestPage($url, $params ?? [], $opts, expectedResource: PrintJob::class);

        return is_array($id) ? $jobs : $jobs->first();
    }

    protected function getExpectedValueResource(string $key): ?string
    {
        return match ($key) {
            'computer' => Computer::class,
            'capabilities' => PrinterCapabilities::class,
            default => null,
        };
    }
}
