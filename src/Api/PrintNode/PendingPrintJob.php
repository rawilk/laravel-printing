<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use Rawilk\Printing\Api\PrintNode\Enums\AuthenticationType;
use Rawilk\Printing\Api\PrintNode\Enums\ContentType;
use Rawilk\Printing\Api\PrintNode\Enums\PrintJobOption;
use Rawilk\Printing\Api\PrintNode\Resources\Printer as PrinterResource;
use Rawilk\Printing\Drivers\PrintNode\Entity\Printer as DriverPrinter;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Exceptions\InvalidOption;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Printing;
use Throwable;

class PendingPrintJob implements Arrayable
{
    use Conditionable;
    use Macroable;

    /** The content to be printed for the new job. */
    public string $content = '';

    /** The content type for the new job. */
    public ContentType $contentType = ContentType::RawBase64;

    /**
     * Options for the new print job.
     *
     * @var array<string, mixed>
     *
     * @see \Rawilk\Printing\Api\PrintNode\Enums\PrintJobOption
     * @see https://www.printnode.com/en/docs/api/curl#printjob-options
     */
    public array $options = [];

    /**
     * The ID of the printer the job will be sent to by PrintNode.
     */
    public int $printerId;

    /** A description of the origin of the print job. */
    public string $source = '';

    /** The title (name) for the new print job. */
    public string $title = '';

    /**
     * The maximum number of seconds PrintNode should retain the print job in the event
     * that the print job cannot be printed immediately. The current default is 14 days
     * or 1,209,600 seconds.
     */
    public ?int $expireAfter = null;

    /**
     * A positive integer specifying the number of times the print job should be
     * delivered to the print queue. This differs from the `copies` option in that
     * this will send the document to the printer multiple times and does not rely
     * on printer driver support.
     *
     * This is the only way to produce multiple copies when raw printing.
     *
     * The default value is `1`.
     */
    public ?int $qty = null;

    /**
     * This is used if the content type is a pdf_uri or raw_uri, and the document
     * needs authentication to access it.
     */
    public ?array $auth = null;

    public static function make(): static
    {
        return new static;
    }

    public function setContent(string $content, bool $encode = true): static
    {
        if ($encode) {
            $content = base64_encode($content);
        }

        $this->content = $content;

        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->content = $url;

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

    public function addPdfFile(string $filePath): static
    {
        $this->addBase64File($filePath);
        $this->contentType = ContentType::PdfBase64;

        return $this;
    }

    public function addRawFile(string $filePath): static
    {
        $this->addBase64File($filePath);
        $this->contentType = ContentType::RawBase64;

        return $this;
    }

    public function addBase64File(string $filePath): static
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

        $this->content = base64_encode($content);

        return $this;
    }

    public function setPrinter(int|PrinterResource|DriverPrinter $printer): static
    {
        $this->printerId = match (true) {
            $printer instanceof PrinterResource => $printer->id,
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

    public function setExpireAfter(int $expireAfter): static
    {
        $this->expireAfter = $expireAfter;

        return $this;
    }

    public function setQty(int $qty): static
    {
        $this->qty = $qty;

        return $this;
    }

    public function setOption(string|PrintJobOption $option, mixed $value): static
    {
        $optionKey = $option instanceof PrintJobOption ? $option->value : $option;

        $this->options[$optionKey] = $value;

        return $this;
    }

    public function setOptions(array $options): static
    {
        // Our API call will verify the options are valid(ish).
        $this->options = $options;

        return $this;
    }

    public function setAuth(
        string $username,
        ?string $password,
        string|AuthenticationType $authenticationType = AuthenticationType::Basic,
    ): static {
        $type = $authenticationType instanceof AuthenticationType
            ? $authenticationType->value
            : $authenticationType;

        $this->auth = [
            'type' => $type,
            'credentials' => [
                'user' => $username,
                'pass' => $password,
            ],
        ];

        return $this;
    }

    /**
     * Verify the provided options are at least somewhat valid.
     */
    public function verifyOptions(): void
    {
        foreach ($this->options as $key => $value) {
            $enum = PrintJobOption::tryFrom($key);

            throw_unless(
                $enum instanceof PrintJobOption,
                InvalidOption::class,
                'The provided option key "' . $key . '" is not valid for a PrintNode request.',
            );

            $enum->validate($value);
        }
    }

    public function toArray(): array
    {
        $this->verifyOptions();

        return [
            'printerId' => $this->printerId,
            'contentType' => $this->contentType->value,
            'content' => $this->content,

            // Optional data
            ...array_filter([
                'title' => $this->title,
                'source' => $this->source,
                'options' => $this->options,
                'expireAfter' => $this->expireAfter,
                'qty' => $this->qty,
                'authentication' => $this->canUseAuth() ? $this->auth : null,
            ], fn ($value): bool => filled($value)),
        ];
    }

    protected function canUseAuth(): bool
    {
        return in_array($this->contentType, [ContentType::RawUri, ContentType::PdfUri], true);
    }
}
