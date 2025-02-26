<?php

declare(strict_types=1);

namespace Rawilk\Printing\Drivers\Cups;

use Rawilk\Printing\Api\Cups\Cups;
use Rawilk\Printing\Api\Cups\Operation;
use Rawilk\Printing\Api\Cups\Request;
use Rawilk\Printing\Api\Cups\Type;
use Rawilk\Printing\Api\Cups\Types\MimeMedia;
use Rawilk\Printing\Api\Cups\Types\NameWithoutLanguage;
use Rawilk\Printing\Api\Cups\Types\Primitive\Enum;
use Rawilk\Printing\Api\Cups\Types\Primitive\Integer;
use Rawilk\Printing\Api\Cups\Types\Primitive\Keyword;
use Rawilk\Printing\Api\Cups\Types\RangeOfInteger;
use Rawilk\Printing\Api\Cups\Types\Uri;
use Rawilk\Printing\Api\Cups\Version;
use Rawilk\Printing\Contracts\PrintJob;
use Rawilk\Printing\Exceptions\InvalidSource;
use Rawilk\Printing\Exceptions\PrintTaskFailed;
use Rawilk\Printing\PrintTask as BasePrintTask;

class PrintTask extends BasePrintTask
{
    protected string $contentType;

    private Cups $api;

    public function __construct()
    {
        parent::__construct();
        $this->api = app(Cups::class);
    }

    public function content($content, string $contentType = ContentType::PDF): self
    {
        if (! $contentType) {
            throw new InvalidSource('Content type is required for the Cups driver.');
        }
        $this->contentType = $contentType;
        parent::content($content);

        return $this;
    }

    public function orientation(string $value): self
    {
        switch ($value) {
            case 'reverse-portrait':
                $orientation = Orientation::REVERSE_PORTRAIT;
                break;
            case 'reverse-landscape':
                $orientation = Orientation::REVERSE_LANDSCAPE;
                break;
            case 'landscape':
                $orientation = Orientation::LANDSCAPE;
                break;
            case 'portrait':
            default:
                $orientation = Orientation::PORTRAIT;
                break;
        }
        $this->option('orientation-requested', new Enum($orientation));

        return $this;
    }

    /**
     * @param  Type|Type[]  $value
     */
    public function option(string $key, $value): self
    {
        $this->options[$key] = $value;

        return $this;
    }

    public function copies(int $copies): self
    {
        $this->option('copies', new Integer($copies));

        return $this;
    }

    public function user(string $name): self
    {
        $this->option('requesting-user-name', new NameWithoutLanguage(iconv('UTF-8', 'ASCII//TRANSLIT', $name)));

        return $this;
    }

    public function range($start, $end = null): self
    {
        if (! array_key_exists('page-ranges', $this->options)) {
            $this->options['page-ranges'] = new RangeOfInteger([$start, $end]);
        } else {
            if (! is_array($this->options['page-ranges'])) {
                $this->options['page-ranges'] = [$this->options['page-ranges']];
            }
            $this->options['page-ranges'][] = new RangeOfInteger([$start, $end]);
        }

        return $this;
    }

    /**
     * @see \Rawilk\Printing\Drivers\Cups\Sides
     */
    public function sides(string $value): self
    {
        $this->option('sides', new Keyword($value));

        return $this;
    }

    public function send(): PrintJob
    {
        $this->ensureValidJob();

        $request = new Request;
        $request->setVersion(Version::V1_1)
            ->setOperation(Operation::PRINT_JOB)
            ->addOperationAttributes(
                [
                    'printer-uri' => new Uri($this->printerId),
                    'document-format' => new MimeMedia($this->contentType),
                    'job-name' => new NameWithoutLanguage($this->resolveJobTitle()),
                ]
            )
            ->addJobAttributes($this->options)
            ->setContent($this->content);

        return $this->api->makeRequest($request)->getJobs()->first();
    }

    protected function ensureValidJob(): void
    {
        if (! $this->printerId) {
            throw PrintTaskFailed::missingPrinterId();
        }

        if (! $this->printSource) {
            throw PrintTaskFailed::missingSource();
        }

        if (! $this->contentType) {
            throw PrintTaskFailed::missingContentType();
        }

        if (! $this->content) {
            throw PrintTaskFailed::noContent();
        }
    }
}
