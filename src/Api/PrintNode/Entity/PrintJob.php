<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Carbon\Carbon;
use InvalidArgumentException;
use Rawilk\Printing\Drivers\PrintNode\ContentType;

class PrintJob extends Entity
{
    protected const VALID_CONTENT_TYPES = [
        ContentType::PDF_BASE64,
        ContentType::RAW_BASE64,
        ContentType::PDF_URI,
        ContentType::RAW_URI,
    ];

    /**
     * The print job's ID.
     */
    public int $id;

    /**
     * The ID of the printer the job is for.
     */
    public string|int $printerId;

    /**
     * The title of the print job.
     */
    public string $title = '';

    /**
     * A description of the origin of the print job.
     */
    public string $source = '';

    /**
     * Various options for the pending print job.
     */
    public array $options = [];

    /**
     * The content to be printed for the pending print job.
     */
    public string $content = '';

    /**
     * The content type for the pending print job.
     */
    public string $contentType = '';

    /**
     * The date the print job was created.
     */
    public ?Carbon $created = null;

    /**
     * The current state of the print job.
     */
    public ?string $state = null;

    /**
     * The printer used for the print job.
     */
    public ?Printer $printer = null;

    public function setPrinter(array $data): self
    {
        $this->printer = new Printer($data);
        $this->printerId = $this->printer->id;

        return $this;
    }

    public function setPrinterId(string|int $printerId): self
    {
        $this->printerId = $printerId;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function setOptions(array $options): self
    {
        $this->options = array_filter(
            $options,
            static function ($key) {
                return in_array($key, [
                    'bin',
                    'collate',
                    'color',
                    'copies',
                    'dpi',
                    'duplex',
                    'fit_to_page',
                    'media',
                    'nup',
                    'pages',
                    'paper',
                    'rotate',
                ], true);
            },
            ARRAY_FILTER_USE_KEY
        );

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setContentType(string $contentType): self
    {
        if (! $this->isValidContentType($contentType)) {
            throw new InvalidArgumentException(
                "Invalid content type \"{$contentType}\". Must be one of: " . implode(', ', static::VALID_CONTENT_TYPES)
            );
        }

        $this->contentType = $contentType;

        return $this;
    }

    public function addPdfFile(string $filePath): self
    {
        $this->addBase64File($filePath);
        $this->contentType = ContentType::PDF_BASE64;

        return $this;
    }

    public function addRawFile(string $filePath): self
    {
        $this->addBase64File($filePath);
        $this->contentType = ContentType::RAW_BASE64;

        return $this;
    }

    public function addBase64File(string $filePath): self
    {
        if (! file_exists($filePath)) {
            throw new InvalidArgumentException("PrintJob - File does not exist: {$filePath}");
        }

        if (! ($content = file_get_contents($filePath))) {
            throw new InvalidArgumentException("PrintJob - Could not open file: {$filePath}");
        }

        $this->content = base64_encode($content);

        return $this;
    }

    public function setCreateTimestamp($date): self
    {
        $this->created = $this->getTimestamp($date);

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'createTimestamp' => $this->created,
        ]);
    }

    protected function isValidContentType(string $type): bool
    {
        return in_array($type, static::VALID_CONTENT_TYPES, true);
    }
}
