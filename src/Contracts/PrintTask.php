<?php

namespace Rawilk\Printing\Contracts;

interface PrintTask
{
    public function content($content): self;

    public function file(string $filePath): self;

    public function url(string $url): self;

    public function jobTitle(string $jobTitle): self;

    public function printer($printerId): self;

    public function option(string $key, $value): self;

    public function range($start, $end = null): self;

    public function tags($tags): self;

    public function tray($tray): self;

    public function copies(int $copies): self;

    public function send(): PrintJob;
}
