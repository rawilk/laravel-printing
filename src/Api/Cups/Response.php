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
use Rawilk\Printing\Api\Cups\Exceptions\ClientError;
use Rawilk\Printing\Api\Cups\Exceptions\UnknownType;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;

class Response
{
    protected Version $version;

    protected int $requestId = 1;

    protected int $statusCode;

    /**
     * @var \Rawilk\Printing\Api\Cups\AttributeGroup[]
     */
    protected array $attributeGroups = [];

    public function __construct(string $binaryData)
    {
        $this->decode($binaryData);
    }

    public function getVersion(): Version
    {
        return $this->version;
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return \Illuminate\Support\Collection<Printer>
     */
    public function getPrinters(): Collection
    {
        $printers = collect();

        foreach ($this->attributeGroups as $group) {
            if ($group instanceof PrinterGroup) {
                $printers->push(new Printer($group->getAttributes()));
            }
        }

        return $printers;
    }

    /**
     * @return \Illuminate\Support\Collection<PrintJob>
     */
    public function getJobs(): Collection
    {
        $jobs = collect();

        foreach ($this->attributeGroups as $group) {
            if ($group instanceof JobGroup) {
                $jobs->push(new PrintJob($group->getAttributes()));
            }
        }

        return $jobs;
    }

    protected function decode(string $binary): void
    {
        $data = unpack('cmajorVer/cminorVer/ncode/NrequestId/ctag', $binary);

        $this->statusCode = $data['code'];
        $this->version = Version::tryFrom($data['majorVer'] . '.' . $data['minorVer']);
        $this->requestId = $data['requestId'];

        $nextTag = $data['tag'];
        $offset = 9;

        $this->attributeGroups = [];
        while (AttributeGroupTag::tryFrom($nextTag) && $nextTag !== AttributeGroupTag::EndOfAttributes->value) {
            $currentTag = $nextTag;
            $attributes = $this->extractAttributes($binary, $offset, $nextTag);
            $className = AttributeGroupTag::getGroupClassByTag($currentTag);
            $this->attributeGroups[] = new $className($attributes);
        }

        $this->checkForSuccessfulResponse();
    }

    protected function extractAttributes(string $binary, int &$offset, mixed &$nextTag)
    {
        $attributes = [];
        $nextTag = -1;

        while (! AttributeGroupTag::tryFrom($nextTag)) {
            $typeTag = (unpack('ctypeTag', $binary, $offset))['typeTag'];
            $type = TypeTag::tryFrom($typeTag);
            $offset++;

            throw_unless(
                $type,
                new UnknownType("Unknown type tag \"{$typeTag}\".")
            );

            $typeClass = $type->getClass();
            [$attrName, $attribute] = $typeClass::fromBinary($binary, $offset);

            // Array of values
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

    protected function checkForSuccessfulResponse(): void
    {
        throw_if(
            $this->statusCode >= 0x0400 && $this->statusCode <= 0x04FF,
            new ClientError($this->getStatusMessage()),
        );

        throw_if(
            $this->statusCode >= 0x0500 && $this->statusCode <= 0x05FF,
            new ClientError($this->getStatusMessage()),
        );
    }

    protected function getStatusMessage(): string
    {
        $group = $this->attributeGroups[$this->getGroupIndex(OperationGroup::class)];
        $attributes = $group->getAttributes();

        if (array_key_exists('status-message', $attributes)) {
            return $attributes['status-message']->value;
        }

        return '';
    }

    protected function getGroupIndex(string $className): int
    {
        foreach ($this->attributeGroups as $index => $attributeGroup) {
            if ($attributeGroup instanceof $className) {
                return $index;
            }
        }

        $this->attributeGroups[] = new $className;

        return count($this->attributeGroups) - 1;
    }
}
