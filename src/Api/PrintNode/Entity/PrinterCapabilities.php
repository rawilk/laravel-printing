<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

class PrinterCapabilities extends Entity
{
    public array $bins = [];

    public bool $collate = false;

    public bool $color = false;

    public int $copies = 0;

    public bool $duplex = false;

    public bool $supportsCustomPaperSize = false;

    public array $dpis = [];

    public ?array $extent = null;

    public array $medias = [];

    public array $nup = [];

    public array $papers = [];

    public ?array $printRate = null;

    // Alias for bins
    public function trays(): array
    {
        return $this->bins;
    }

    public function setPrintrate(?array $printRate): self
    {
        $this->printRate = $printRate;

        return $this;
    }

    public function setSupportsCustomPaperSize(bool $supports): self
    {
        $this->supportsCustomPaperSize = $supports;

        return $this;
    }
}
