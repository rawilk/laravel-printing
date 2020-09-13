<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Illuminate\Support\Str;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob as RawilkPrintJob;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;
use Smalot\Cups\Manager\JobManager;
use Smalot\Cups\Manager\PrinterManager;
use Smalot\Cups\Model\Job;
use Smalot\Cups\Model\Printer as SmalotPrinter;

class PrintTask extends BasePrintTask
{
    protected JobManager $jobManager;
    protected PrinterManager $printerManager;
    protected Job $job;
    protected SmalotPrinter $printer;

    public function __construct(JobManager $jobManager, PrinterManager $printerManager)
    {
        parent::__construct();

        $this->jobManager = $jobManager;
        $this->printerManager = $printerManager;
        $this->job = new Job;
    }

    public function content($content, string $contentType = ContentType::PDF): self
    {
        if (! $contentType) {
            throw new InvalidSource('Content type is required for the CUPS driver.');
        }

        parent::content($content);

        $this->job->addText($this->content, '', $contentType);

        return $this;
    }

    public function file(string $filePath, string $contentType = ContentType::PDF): self
    {
        if (! $contentType) {
            throw new InvalidSource('Content type is required for the CUPS driver.');
        }

        parent::file($filePath);

        $this->job->addFile($filePath, '', $contentType);

        return $this;
    }

    public function url(string $url, string $contentType = ContentType::PDF): self
    {
        if (! $contentType) {
            throw new InvalidSource('Content type is required for the CUPS driver.');
        }

        parent::url($url);

        $this->job->addText($this->content, '', $contentType);

        return $this;
    }

    public function printer($printerId): self
    {
        parent::printer($printerId);

        $this->printer = $printerId instanceof Printer
            ? $printerId->cupsPrinter()
            : $this->printerManager->findByUri($printerId);

        return $this;
    }

    public function range($start, $end = null): self
    {
        $range = $start;

        if (! $end && Str::endsWith($range, '-')) {
            // If an end page is not set, we will default the end to a really high number
            // that hopefully won't ever be exceeded when printing. The reason we have to
            // provide an end page is because the library we rely on for CUPS printing
            // doesn't allow "printing of all pages", i.e. 1- syntax.
            // see: https://github.com/smalot/cups-ipp/issues/7
            $range .= '999';
        } elseif ($end) {
            $range = Str::endsWith($range, '-')
                ? $range . $end
                : "{$range}-{$end}";
        }

        $this->job->setPageRanges($range);

        return $this;
    }

    public function tray($tray): self
    {
        if (! empty($tray)) {
            $this->job->addAttribute('media-source', $tray);
        }

        return $this;
    }

    public function copies(int $copies): self
    {
        $this->job->setCopies($copies);

        return $this;
    }

    public function send(): PrintJob
    {
        if (! $this->printerId || ! isset($this->printer)) {
            throw PrintTaskFailed::missingPrinterId();
        }

        $this->job->setName($this->resolveJobTitle());

        foreach ($this->options as $key => $value) {
            $this->job->addAttribute($key, $value);
        }

        if (! $this->job->getPageRanges()) {
            // Print all pages if a page range is not specified.
            $this->range('1-');
        }

        $success = $this->jobManager->send($this->printer, $this->job);

        if (! $success) {
            throw PrintTaskFailed::driverFailed('CUPS print task failed to execute.');
        }

        return new RawilkPrintJob($this->job, new Printer($this->printer, $this->jobManager));
    }
}
