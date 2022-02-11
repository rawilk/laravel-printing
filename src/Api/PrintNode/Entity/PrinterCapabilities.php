<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Illuminate\Contracts\Support\Arrayable;

class PrinterCapabilities extends Entity implements Arrayable
{
    public array $bins = [];
    public bool $collate = false;
    public bool $color = false;
    public int $copies = 0;
    public bool $duplex = false;
    public bool $supportsCustomPaperSize = false;
    public array $dpis = [];
    public null|array $extent = null;
    public array $medias = [];
    public array $nup = [];
    public array $papers = [];
    public null|array $printRate = null;

    // Alias for bins
    public function trays(): array
    {
        return $this->bins;
    }

    public function setPrintrate(null|array $printRate): self
    {
        $this->printRate = $printRate;

        return $this;
    }

    public function setSupportsCustomPaperSize(bool $supports): self
    {
        $this->supportsCustomPaperSize = $supports;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'bins' => $this->bins,
            'collate' => $this->collate,
            'color' => $this->color,
            'copies' => $this->copies,
            'duplex' => $this->duplex,
            'supportsCustomPaperSize' => $this->supportsCustomPaperSize,
            'dpis' => $this->dpis,
            'extent' => $this->extent,
            'medias' => $this->medias,
            'nup' => $this->nup,
            'papers' => $this->papers,
            'printRate' => $this->printRate,
        ];
    }
}
