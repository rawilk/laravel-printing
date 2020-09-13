<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups\Entity;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use JsonSerializable;
use Rawilk\Printing\Contracts\Printer as PrinterContracts;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Model\JobInterface;
use Smalot\Cups\Model\Printer as SmalotPrinter;

class Printer implements PrinterContracts, Arrayable, JsonSerializable
{
    protected SmalotPrinter $printer;
    protected JobManager $jobManager;

    protected array $capabilities;

    public function __construct(SmalotPrinter $printer, JobManager $jobManager)
    {
        $this->printer = $printer;
        $this->jobManager = $jobManager;
    }

    public function cupsPrinter(): SmalotPrinter
    {
        return $this->printer;
    }

    public function capabilities(): array
    {
        if (! isset($this->capabilities)) {
            $this->capabilities = $this->printer->getAttributes();
        }

        return $this->capabilities;
    }

    public function description(): ?string
    {
        return Arr::get($this->capabilities(), 'printer-info', [])[0] ?? null;
    }

    public function id(): string
    {
        return $this->printer->getUri();
    }

    public function isOnline(): bool
    {
        return strtolower($this->status()) === 'online';
    }

    public function name(): ?string
    {
        return $this->printer->getName();
    }

    public function status(): string
    {
        return $this->printer->getStatus();
    }

    public function trays(): array
    {
        return Arr::get($this->capabilities(), 'media-source-supported', []);
    }

    /**
     * @param array $params
     *  - Possible Params:
     *    -- limit => int
     *    -- status => 'completed', 'not-completed'
     * @return \Illuminate\Support\Collection
     */
    public function jobs(array $params = []): Collection
    {
        $supportedStatuses = ['completed', 'not-completed'];
        $limit = max(0, Arr::get($params, 'limit', 0));
        $status = Arr::get($params, 'status', 'completed');

        if (! in_array($status, $supportedStatuses, true)) {
            $status = 'completed';
        }

        $jobs = $this->jobManager->getList($this->printer, false, $limit, $status);

        return collect($jobs)
            ->map(fn (JobInterface $job) => new PrintJob($job, $this))
            ->values();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
            'online' => $this->isOnline(),
            'status' => $this->status(),
            'trays' => $this->trays(),
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
