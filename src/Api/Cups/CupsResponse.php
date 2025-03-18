<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Illuminate\Support\Collection;
use Rawilk\Printing\Api\Cups\Attributes\JobGroup;
use Rawilk\Printing\Api\Cups\Attributes\OperationGroup;
use Rawilk\Printing\Api\Cups\Attributes\PrinterGroup;
use Rawilk\Printing\Api\Cups\Enums\AttributeGroupTag;
use Rawilk\Printing\Api\Cups\Enums\TypeTag;
use Rawilk\Printing\Api\Cups\Enums\Version;
use Rawilk\Printing\Api\Cups\Exceptions\CupsRequestFailed;
use Rawilk\Printing\Api\Cups\Exceptions\UnknownType;
use Rawilk\Printing\Api\Cups\Resources\Printer;
use Rawilk\Printing\Api\Cups\Resources\PrintJob;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;

/**
 * @credit vatsake - Most of the logic here comes from the code from a Response class
 *      written in PR #92
 */
class CupsResponse
{
    public int $statusCode;

    public ?Version $version;

    public int $requestId;

    /** @var array<class-string<\Rawilk\Printing\Api\Cups\AttributeGroup>, \Rawilk\Printing\Api\Cups\AttributeGroup> */
    public array $attributeGroups = [];

    public function __construct(
        public int $code,
        public string $body,
        public array $headers,
        public ?RequestOptions $opts = null,
    ) {
        $this->decodeBody($body);
    }

    /**
     * @return Collection<int, Printer>
     */
    public function printers(): Collection
    {
        return collect($this->attributeGroups[PrinterGroup::class])
            ->map(function (PrinterGroup $group) {
                $attributes = $group->toArray();
                $uri = $group['printer-uri-supported'] ?? [];

                $attributes['uri'] = is_array($uri) ? ($uri[0] ?? null) : $uri->value;

                return Printer::make($attributes, $this->opts);
            });
    }

    /**
     * @return Collection<int, \Rawilk\Printing\Api\Cups\Resources\PrintJob>
     */
    public function jobs(): Collection
    {
        return collect($this->attributeGroups[JobGroup::class])
            ->map(function (JobGroup $group) {
                $attributes = $group->toArray();
                $uri = $group['job-uri'] ?? [];

                $attributes['uri'] = is_array($uri) ? ($uri[0] ?? null) : $uri->value;

                return PrintJob::make($attributes, $this->opts);
            });
    }

    protected function decodeBody(string $binary): void
    {
        $data = unpack('cmajorVer/cminorVer/ncode/NrequestId/ctag', $binary);

        $this->statusCode = (int) $data['code'];
        $this->version = Version::tryFrom($data['majorVer'] . '.' . $data['minorVer']);
        $this->requestId = (int) ($data['requestId'] ?? 1);

        $nextTag = $data['tag'];
        $offset = 9;

        while (AttributeGroupTag::tryFrom($nextTag) && $nextTag !== AttributeGroupTag::EndOfAttributes->value) {
            $currentTag = $nextTag;
            $attributes = $this->extractAttributes($binary, $offset, $nextTag);

            $className = AttributeGroupTag::getGroupClassByTag($currentTag);

            if (! array_key_exists($className, $this->attributeGroups)) {
                $this->attributeGroups[$className] = [];
            }

            $this->attributeGroups[$className][] = new $className($attributes);
        }

        $this->throwIfUnsuccessfulResponse();
    }

    protected function extractAttributes(string $binary, int &$offset, mixed &$nextTag): array
    {
        $attributes = [];
        $nextTag = -1;

        while (! AttributeGroupTag::tryFrom($nextTag)) {
            $typeTag = (unpack('ctypeTag', $binary, $offset))['typeTag'];
            $type = TypeTag::tryFrom($typeTag);
            $offset++;

            throw_unless(
                $type instanceof TypeTag,
                UnknownType::class,
                'Unknown type tag "' . $typeTag . '"',
            );

            $typeClass = $type->getClass();

            /** @var string $attrName */
            [$attrName, $attribute] = $typeClass::fromBinary($binary, $offset);

            if ($attrName === '') {
                $index = array_key_last($attributes);
                $lastAttr = $attributes[$index];

                if (! is_array($lastAttr)) {
                    $attributes[$index] = [$lastAttr];
                }

                $attributes[$index][] = $attribute;
            } else {
                $attributes[$attrName] = $attribute;
            }

            $nextTag = (unpack('ctag', $binary, $offset))['tag'];
        }

        $offset++;

        return $attributes;
    }

    protected function throwIfUnsuccessfulResponse(): void
    {
        throw_if(
            $this->statusCode >= 0x0400 && $this->statusCode <= 0x04FF,
            CupsRequestFailed::class,
            $this->getStatusMessage(),
        );

        throw_if(
            $this->statusCode >= 0x0500 && $this->statusCode <= 0x05FF,
            CupsRequestFailed::class,
            $this->getStatusMessage(),
        );
    }

    protected function getStatusMessage(): string
    {
        /** @var null|\Rawilk\Printing\Api\Cups\AttributeGroup $group */
        $group = $this->attributeGroups[OperationGroup::class][0] ?? null;
        if ($group === null) {
            return 'An unknown error occurred';
        }

        return $group['status-message']?->value ?? 'An unknown error occurred';
    }
}
