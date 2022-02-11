<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Illuminate\Support\Collection;

class PrintJobs extends Entity
{
    /** @var \Illuminate\Support\Collection<int, PrintJob> */
    public Collection $jobs;

    public function __construct(array $data = [])
    {
        $this->jobs = collect();

        parent::__construct($data);
    }

    public function setJobs(array $jobs): self
    {
        $this->jobs = collect($jobs)->map(fn (array $job) => new PrintJob($job));

        return $this;
    }
}
