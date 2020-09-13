<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use Illuminate\Support\Str;
use PrintNode\Client;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob as RawilkPrintJob;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintNodePrintJob;
use Rawilk\Printing\Exceptions\InvalidOption;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    protected Client $client;
    protected PrintNodePrintJob $job;

    public function __construct(Client $client)
    {
        parent::__construct();

        $this->client = $client;
        $this->job = new PrintNodePrintJob($this->client);
    }

    public function content($content): self
    {
        $this->job->content = base64_encode($content);
        $this->job->contentType = ContentType::RAW_BASE64;

        return $this;
    }

    public function file(string $filePath): self
    {
        if (! file_exists($filePath)) {
            throw InvalidSource::fileNotFound($filePath);
        }

        // PrintNode will set the content type for us on the job object.
        $this->job->addPdfFile($filePath);

        return $this;
    }

    public function url(string $url, bool $raw = false): self
    {
        $this->job->content = $url;
        $this->job->contentType = $raw ? ContentType::RAW_URI : ContentType::PDF_URI;

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

    public function send(): PrintJob
    {
        if (! $this->printerId) {
            throw PrintTaskFailed::missingPrinterId();
        }

        $this->job->printer = $this->printerId;
        $this->job->title = $this->resolveJobTitle();
        $this->job->source = $this->printSource;
        $this->job->setOptions($this->options);

        $printJobId = $this->client->createPrintJob($this->job);

        if (! $printJobId) {
            throw PrintTaskFailed::driverFailed('PrintNode print job failed to execute.');
        }

        $this->job->setId($printJobId);

        return new RawilkPrintJob($this->job);
    }
}
