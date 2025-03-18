<?php

declare(strict_types=1);

namespace Rawilk\Printing\Contracts;

use BackedEnum;

interface PrintTask
{
    public function content($content): self;

    public function file(string $filePath): self;

    public function url(string $url): self;

    public function jobTitle(string $jobTitle): self;

    public function printer(Printer|string|null|int $printerId): self;

    public function option(BackedEnum|string $key, $value): self;

    public function range($start, $end = null): self;

    public function tags($tags): self;

    public function tray($tray): self;

    public function copies(int $copies): self;

    public function send(): PrintJob;
}
