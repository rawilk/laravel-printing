<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use Illuminate\Support\Str;
use Rawilk\Printing\Api\PrintNode\Entity\PrintJob as PrintNodePrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNode as PrintNodeApi;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob as RawilkPrintJob;
use Rawilk\Printing\Exceptions\InvalidOption;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    protected PrintNodePrintJob $job;

    public function __construct(protected PrintNodeApi $api)
    {
        parent::__construct();

        $this->job = new PrintNodePrintJob;
    }

    public function content($content, string $contentType = ContentType::RAW_BASE64): self
    {
        if (! $contentType) {
            throw new InvalidSource('Content type is required for the PrintNode driver.');
        }

        parent::content($content);
        $this->job->setContent(base64_encode($content))->setContentType($contentType);

        return $this;
    }

    public function file(string $filePath): self
    {
        if (! file_exists($filePath)) {
            throw InvalidSource::fileNotFound($filePath);
        }

        // Content type will be set to pdf_base64 by the job.
        $this->job->addPdfFile($filePath);

        return $this;
    }

    public function url(string $url, bool $raw = false): self
    {
        $this->job
            ->setContent($url)
            ->setContentType($raw ? ContentType::RAW_URI : ContentType::PDF_URI);

        // TODO: set authentication if credentials passed in

        return $this;
    }

    public function range($start, $end = null): self
    {
        $range = $start;

        if (! $end && ! Str::contains($range, [',', '-'])) {
            $range = "{$range}-"; // print all pages starting from $start
        } elseif ($end) {
            $range = "{$start}-{$end}";
        }

        return $this->option('pages', $range);
    }

    public function tray($tray): self
    {
        return $this->option('bin', $tray);
    }

    public function copies(int $copies): self
    {
        if ($copies < 1) {
            throw InvalidOption::invalidOption('The `copies` option must be greater than 1.');
        }

        return $this->option('copies', $copies);
    }

    public function fitToPage(bool $fitToPage): self
    {
        return $this->option('fit_to_page', $fitToPage);
    }

    public function paper(string $paper): self
    {
        return $this->option('paper', $paper);
    }

    public function send(): PrintJob
    {
        $this->ensureValidJob();

        $this->job
            ->setPrinterId($this->printerId)
            ->setTitle($this->resolveJobTitle())
            ->setSource($this->printSource)
            ->setOptions($this->options);

        $printJob = $this->api->createPrintJob($this->job);

        return new RawilkPrintJob($printJob);
    }

    protected function ensureValidJob(): void
    {
        if (! $this->printerId) {
            throw PrintTaskFailed::missingPrinterId();
        }

        if (! $this->printSource) {
            throw PrintTaskFailed::missingSource();
        }

        if (! $this->job->contentType) {
            throw PrintTaskFailed::missingContentType();
        }

        if (! $this->job->content) {
            throw PrintTaskFailed::noContent();
        }
    }
}
