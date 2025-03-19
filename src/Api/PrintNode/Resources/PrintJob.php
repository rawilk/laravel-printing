<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Exceptions\PrintTaskFailed;

/**
 * A `PrintJob` represents a print job in the PrintNode API.
 *
 * @property-read int $id
 * @property-read string $createTimestamp Time and date the print job was created
 * @property-read \Rawilk\Printing\Api\PrintNode\Resources\Printer $printer The printer the job was sent to
 * @property-read string $title The title of the print job
 * @property-read string $contentType The content type of the print job
 * @property-read string $source A string that describes the origin of the print job
 * @property-read string|null $expireAt The time at which the print job expires
 * @property-read string $state The current state of the print job
 */
class PrintJob extends PrintNodeApiResource
{
    use ApiOperations\All;
    use ApiOperations\Delete;
    use ApiOperations\Retrieve;
    use Concerns\HasDateAttributes;

    /**
     * Create and send a new print job through the PrintNode API.
     */
    public static function create(array|PendingPrintJob $params, null|array|RequestOptions $opts = null): static
    {
        $data = $params instanceof PendingPrintJob ? $params->toArray() : $params;

        $url = static::classUrl();

        /** @var \Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse $response */
        [$response, $opts] = static::_staticRequest('post', $url, $data, $opts);

        // PrintNode only returns the ID of the new job, so we need to perform another api call
        // to fetch the new job, unfortunately.
        $jobId = $response->body;

        throw_unless(
            filled($jobId) && is_int($jobId),
            PrintTaskFailed::noJobCreated(),
        );

        $instance = new static($jobId, $opts);
        $instance->refresh();

        $instance->setLastResponse($response);

        return $instance;
    }

    public function createdAt(): ?CarbonInterface
    {
        return $this->parseDate($this->createTimestamp);
    }

    public function expiresAt(): ?CarbonInterface
    {
        return $this->parseDate($this->expireAt);
    }

    /**
     * Alias for `delete()`.
     */
    public function cancel(?array $params = null, null|array|RequestOptions $opts = null): static
    {
        return $this->delete($params, $opts);
    }

    /**
     * Get all the states that PrintNode has reported for the job.
     *
     * @return Collection<int, PrintJobState>
     */
    public function getStates(?array $params = null, null|array|RequestOptions $opts = null): Collection
    {
        $url = $this->instanceUrl() . '/states';

        return static::_requestPage($url, $params ?? [], $opts, expectedResource: PrintJobState::class);
    }

    protected function getExpectedValueResource(string $key): ?string
    {
        return match ($key) {
            'printer' => Printer::class,
            default => null,
        };
    }
}
