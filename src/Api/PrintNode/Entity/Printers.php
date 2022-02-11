<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Illuminate\Support\Collection;

class Printers extends Entity
{
    /** @var \Illuminate\Support\Collection<int, Printer> */
    public Collection $printers;

    public function __construct(array $data = [])
    {
        $this->printers = collect();

        parent::__construct($data);
    }

    public function setPrinters(array $printers): self
    {
        $this->printers = collect($printers)->map(fn (array $printer) => new Printer($printer));

        return $this;
    }
}
