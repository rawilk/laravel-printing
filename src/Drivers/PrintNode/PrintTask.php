<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\PrintNode;

use BackedEnum;
use Illuminate\Support\Str;
use Rawilk\Printing\Api\PrintNode\Enums\AuthenticationType;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;
use Rawilk\Printing\Api\PrintNode\Enums\PrintJobOption;
use Rawilk\Printing\Api\PrintNode\PendingPrintJob;
use Rawilk\Printing\Api\PrintNode\PrintNodeClient;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Drivers\PrintNode\Entity\PrintJob as PrintJobContract;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    protected PendingPrintJob $pendingJob;

    public function __construct(protected PrintNodeClient $client)
    {
        parent::__construct();

        $this->pendingJob = PendingPrintJob::make();
    }

    public function content($content, string|ContentType $contentType = ContentType::RawBase64): static
    {
        parent::content($content);

        $this->pendingJob
            ->setContent($content)
            ->setContentType($contentType);

        return $this;
    }

    public function file(string $filePath): static
    {
        throw_unless(
            file_exists($filePath),
            InvalidSource::fileNotFound($filePath),
        );

        $this->pendingJob->addPdfFile($filePath);

        return $this;
    }

    public function url(string $url, bool $raw = false): static
    {
        $this->pendingJob
            ->setUrl($url)
            ->setContentType($raw ? ContentType::RawUri : ContentType::PdfUri);

        return $this;
    }

    public function option(BackedEnum|string $key, $value): static
    {
        $this->pendingJob->setOption($key, $value);

        return $this;
    }

    public function range($start, $end = null): static
    {
        $range = $start;

        if (! $end && (! Str::contains($range, [',', '-']))) {
            $range = "{$range}-"; // print all pages starting from $start
        } elseif ($end) {
            $range = "{$start}-{$end}";
        }

        return $this->option(PrintJobOption::Pages, $range);
    }

    public function tray($tray): static
    {
        return $this->option(PrintJobOption::Bin, $tray);
    }

    public function copies(int $copies): static
    {
        return $this->option(PrintJobOption::Copies, $copies);
    }

    // region PrintNode specific setters
    public function fitToPage(bool $condition): static
    {
        return $this->option(PrintJobOption::FitToPage, $condition);
    }

    public function paper(string $paper): static
    {
        return $this->option(PrintJobOption::Paper, $paper);
    }

    public function expireAfter(int $expireAfter): static
    {
        $this->pendingJob->setExpireAfter($expireAfter);

        return $this;
    }

    public function printQty(int $qty): static
    {
        $this->pendingJob->setQty($qty);

        return $this;
    }

    public function withAuth(
        string $username,
        ?string $password,
        string|AuthenticationType $authenticationType = AuthenticationType::Basic,
    ): static {
        $this->pendingJob->setAuth($username, $password, $authenticationType);

        return $this;
    }
    // endregion

    public function send(null|array|RequestOptions $opts = null): PrintJobContract
    {
        $this->ensureValidJob();

        $this->pendingJob
            ->setPrinter($this->printerId)
            ->setTitle($this->resolveJobTitle())
            ->setSource($this->printSource);

        $printJob = $this->client->printJobs->create($this->pendingJob, $opts);

        return new PrintJobContract($printJob);
    }

    protected function ensureValidJob(): void
    {
        throw_unless(
            filled($this->printerId),
            PrintTaskFailed::missingPrinterId(),
        );

        throw_unless(
            filled($this->printSource),
            PrintTaskFailed::missingSource(),
        );

        throw_unless(
            filled($this->pendingJob->contentType),
            PrintTaskFailed::missingContentType(),
        );

        throw_unless(
            filled($this->pendingJob->content),
            PrintTaskFailed::noContent(),
        );
    }
}
