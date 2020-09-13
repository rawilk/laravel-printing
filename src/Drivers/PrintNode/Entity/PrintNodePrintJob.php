<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode\Entity;

use PrintNode\Entity\PrintJob as PrintNodeEntity;

class PrintNodePrintJob extends PrintNodeEntity
{
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
