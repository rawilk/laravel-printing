<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Illuminate\Support\Collection;

class Computers extends Entity
{
    /** @var \Illuminate\Support\Collection<int, Computer> */
    public Collection $computers;

    public function __construct(array $data = [])
    {
        $this->computers = collect();

        parent::__construct($data);
    }

    public function setComputers(array $computers): self
    {
        $this->computers = collect($computers)->map(fn (array $computer) => new Computer($computer));

        return $this;
    }
}
