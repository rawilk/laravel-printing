<?php

declare(strict_types=1);

namespace Rawilk\Printing;

use Rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Contracts\PrintTask as PrintTaskContract;
use Rawilk\Printing\Exceptions\InvalidSource;

abstract class PrintTask implements PrintTaskContract
{
    protected string $jobTitle = '';
    protected array $options = [];
    protected string $content = '';
    protected string $printSource;

    /** @var string|mixed */
    protected $printerId;

    public function __construct()
    {
        $this->printSource = config('app.name');
    }

    public function content($content): self
    {
        $this->content = $content;

        return $this;
    }

    public function file(string $filePath): self
    {
        if (! file_exists($filePath)) {
            throw InvalidSource::fileNotFound($filePath);
        }

        $this->content = file_get_contents($filePath);

        return $this;
    }

    public function url(string $url): self
    {
        if (! preg_match('/^https?:\/\//', $url)) {
            throw InvalidSource::invalidUrl($url);
        }

        $this->content = file_get_contents($url);

        return $this;
    }

    public function jobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function printer($printerId): self
    {
        if ($printerId instanceof Printer) {
            $printerId = $printerId->id();
        }

        $this->printerId = $printerId;

        return $this;
    }

    public function printSource(string $printSource): self
    {
        $this->printSource = $printSource;

        return $this;
    }

    /**
     * Not all drivers may support tagging jobs.
     */
    public function tags($tags): self
    {
        return $this;
    }

    /**
     * Not all drivers may support this feature.
     */
    public function tray($tray): self
    {
        return $this;
    }

    /**
     * Not all drivers might support this option.
     */
    public function copies(int $copies): self
    {
        return $this;
    }

    public function option(string $key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    protected function resolveJobTitle(): string
    {
        if ($this->jobTitle) {
            return $this->jobTitle;
        }

        return 'job_' . uniqid('', false) . '_' . date('Ymdhis');
    }
}
