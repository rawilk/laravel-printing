<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\Cups\Enums\ContentType;
use Rawilk\Printing\Api\Cups\Enums\Operation;
use Rawilk\Printing\Api\Cups\Enums\OperationAttribute;
use Rawilk\Printing\Api\Cups\Enums\Version;
use Rawilk\Printing\Api\Cups\Resources\Printer as PrinterResource;
use Rawilk\Printing\Drivers\Cups\Entity\Printer as DriverPrinter;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Printing;
use Throwable;

class PendingPrintJob
{
    use Conditionable;
    use Macroable;

    /** The content to be printed for the new job */
    public string $content = '';

    /** The content type for the new job */
    public ContentType $contentType = ContentType::Pdf;

    /**
     * The options for the new print job.
     *
     * @var array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    public array $options = [];

    /** The uri (id) of the printer to send the job to. */
    public string $printerUri;

    /** A description of the origin of the print job. */
    public string $source = '';

    /** The title (name) for the new print job. */
    public string $title = '';

    public static function make(): static
    {
        return new static;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function addFile(string $filePath, string|ContentType $contentType = ContentType::Pdf): static
    {
        throw_unless(
            file_exists($filePath),
            InvalidSource::fileNotFound($filePath),
        );

        try {
            $content = file_get_contents($filePath);
        } catch (Throwable) {
            throw InvalidSource::cannotOpenFile($filePath);
        }

        if (blank($content)) {
            Printing::getLogger()?->error("No content retrieved from file: {$filePath}");
        }

        $this->content = $content;

        $this->setContentType($contentType);

        return $this;
    }

    public function setContentType(string|ContentType $contentType): static
    {
        $enum = is_string($contentType)
            ? ContentType::tryFrom($contentType)
            : $contentType;

        if (! $enum instanceof ContentType) {
            throw new InvalidArgument(
                'Invalid content type "' . $contentType . '". Must be one of: ' . implode(', ', array_column(ContentType::cases(), 'value'))
            );
        }

        $this->contentType = $enum;

        return $this;
    }

    public function setOption(string|OperationAttribute $option, Type $value): static
    {
        $optionKey = $option instanceof OperationAttribute ? $option->value : $option;

        $this->options[$optionKey] = $value;

        return $this;
    }

    public function setPrinter(string|PrinterResource|DriverPrinter $printer): static
    {
        $this->printerUri = match (true) {
            $printer instanceof PrinterResource => $printer->uri,
            $printer instanceof DriverPrinter => $printer->id(),
            default => $printer,
        };

        return $this;
    }

    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function range($start, $end = null): static
    {
        $attr = OperationAttribute::PageRanges;
        $key = $attr->value;
        $type = $attr->toType([$start, $end]);

        if (! array_key_exists($key, $this->options)) {
            $this->options[$key] = $type;

            return $this;
        }

        if (! is_array($this->options[$key])) {
            $this->options[$key] = [$this->options[$key]];
        }

        $this->options[$key][] = $type;

        return $this;
    }

    public function toPendingRequest(): PendingRequest
    {
        return (new PendingRequest)
            ->setVersion(Version::V1_1)
            ->setOperation(Operation::PrintJob)
            ->addOperationAttributes([
                OperationAttribute::PrinterUri->value => OperationAttribute::PrinterUri->toType($this->printerUri),
                OperationAttribute::DocumentFormat->value => OperationAttribute::DocumentFormat->toType($this->contentType->value),
                OperationAttribute::JobName->value => OperationAttribute::JobName->toType($this->title),
            ])
            ->addJobAttributes($this->options)
            ->setContent($this->content);
    }
}
