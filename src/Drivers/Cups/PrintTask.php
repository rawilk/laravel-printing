<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use BackedEnum;
use Rawilk\Printing\Api\Cups\CupsClient;
use Rawilk\Printing\Api\Cups\Enums\ContentType;
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;
use Rawilk\Printing\Api\Cups\Enums\Orientation;
use Rawilk\Printing\Api\Cups\Enums\Side;
use Rawilk\Printing\Api\Cups\PendingPrintJob;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob as PrintJobContract;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    protected PendingPrintJob $pendingJob;

    public function __construct(protected CupsClient $client)
    {
        parent::__construct();

        $this->pendingJob = PendingPrintJob::make();
    }

    public function content($content, string|ContentType $contentType = ContentType::Pdf): static
    {
        $this->pendingJob
            ->setContent($content)
            ->setContentType($contentType);

        return $this;
    }

    public function file(string $filePath, string|ContentType $contentType = ContentType::Pdf): static
    {
        $this->pendingJob->addFile($filePath, $contentType);

        return $this;
    }

    public function url(string $url): static
    {
        parent::url($url);

        $this->pendingJob->setContent($this->content);

        return $this;
    }

    public function option(BackedEnum|string $key, $value): static
    {
        $this->pendingJob->setOption($key, $value);

        return $this;
    }

    public function copies(int $copies): static
    {
        $this->pendingJob->setOption(
            OperationAttribute::Copies,
            OperationAttribute::Copies->toType($copies),
        );

        return $this;
    }

    public function range($start, $end = null): static
    {
        $this->pendingJob->range($start, $end);

        return $this;
    }

    // region Cups specific setters
    public function contentType(string|ContentType $contentType): static
    {
        $this->pendingJob->setContentType($contentType);

        return $this;
    }

    public function orientation(string|Orientation $value): static
    {
        $enum = $value instanceof Orientation
            ? $value
            : match ($value) {
                'reverse-portrait' => Orientation::ReversePortrait,
                'reverse-landscape' => Orientation::ReverseLandscape,
                'landscape' => Orientation::Landscape,
                default => Orientation::Portrait,
            };

        $this->pendingJob->setOption(
            OperationAttribute::OrientationRequested,
            OperationAttribute::OrientationRequested->toType($enum->value),
        );

        return $this;
    }

    public function sides(string|Side $value): static
    {
        $enum = is_string($value)
            ? Side::tryFrom($value)
            : $value;

        if (! $enum instanceof Side) {
            throw new InvalidArgument(
                'Invalid side "' . $value . '" for the cups driver. Accepted values are: ' .
                implode(', ', array_column(Side::cases(), 'value')),
            );
        }

        return $this->option(
            OperationAttribute::Sides,
            OperationAttribute::Sides->toType($enum->value),
        );
    }

    public function user(string $name): static
    {
        $this->pendingJob->setOption(
            OperationAttribute::RequestingUserName,
            OperationAttribute::RequestingUserName->toType($name),
        );

        return $this;
    }
    // endregion

    public function send(array|null|RequestOptions $opts = null): PrintJobContract
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
