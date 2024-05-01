<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Exceptions\UnknownType;
use Rawilk\Printing\Drivers\Cups\Entity\Printer;
use Rawilk\Printing\Drivers\Cups\Entity\PrintJob;

class Response
{
    private Version $version;
    private int $requestId = 1;
    private int $statusCode;

    /**
     * @var \Rawilk\Printing\Api\Cups\AttributeGroup[]
     */
    private array $attributeGroups = [];

    public function __construct(string $binaryData)
    {
        $this->decode($binaryData);
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    private function decode(string $binary)
    {
        $data = unpack("cmajorVer/cminorVer/ncode/NrequestId/ctag", $binary);

        $this->statusCode = $data['code'];
        $this->version = Version::tryFrom($data['majorVer'] . '.' . $data['minorVer']);
        $this->requestId = $data['requestId'];

        $nextTag = $data['tag'];
        $offset = 9;

        $this->attributeGroups = [];
        while (AttributeGroupTag::tryFrom($nextTag) && $nextTag !== AttributeGroupTag::END_OF_ATTRIBUTES->value) {
            $currentTag = $nextTag;
            $attributes = $this->extractAttributes($binary, $offset, $nextTag);
            $className = AttributeGroupTag::getGroupClassByTag($currentTag);
            $this->attributeGroups[] = new $className($attributes);
        }

        $this->checkSuccessfulResponse();
    }

    private function extractAttributes(string $binary, int &$offset, mixed &$nextTag)
    {
        $attributes = [];
        $nextTag = -1;
        while (!AttributeGroupTag::tryFrom($nextTag)) {
            $typeTag = (unpack('ctypeTag', $binary, $offset))['typeTag'];
            $type = TypeTag::tryFrom($typeTag);
            $offset++;

            $nameLen = (unpack('n', $binary, $offset))[1];
            $offset += 2;

            $attrName = unpack('a' . $nameLen, $binary, $offset)[1];
            $offset += $nameLen;

            if (!$type) {
                throw new UnknownType("Unknown type tag \"$typeTag\" for attribute \"$attrName\".");
            }

            $valueLen = (unpack('n', $binary, $offset))[1];
            $offset += 2;

            $typeClass = $type->getClass();
            $attribute = $typeClass::fromBinary(substr($binary, $offset, $valueLen), $valueLen);
            $offset += $valueLen;

            // Array of values
            if ($attrName === '') {
                $lastAttr = $attributes[array_key_last($attributes)];

                if ($typeTag !== TypeTag::RANGEOFINTEGER->value && gettype($lastAttr->value) !== 'array') {
                    $lastAttr->value = [$lastAttr->value];
                }

                if ($typeTag == TypeTag::RANGEOFINTEGER->value) {
                    $lastAttr->value[] = $attribute->value[0];
                } else {
                    $lastAttr->value[] = $attribute->value;
                }
            } else {
                $attributes[$attrName] = $attribute;
            }

            $nextTag = (unpack("ctag", $binary, $offset))['tag'];
        }
        $offset++;

        return $attributes;
    }

    private function checkSuccessfulResponse()
    {
        if ($this->statusCode >= 0x0400 && $this->statusCode <= 0x04FF) {
            throw new \Rawilk\Printing\Api\Cups\Exceptions\ClientError($this->getStatusMessage());
        } elseif ($this->statusCode >= 0x0500 && $this->statusCode <= 0x05FF) {
            throw new \Rawilk\Printing\Api\Cups\Exceptions\ClientError($this->getStatusMessage());
        }
    }

    private function getStatusMessage(): string
    {
        $group = $this->attributeGroups[$this->getGroupIndex(\Rawilk\Printing\Api\Cups\Attributes\OperationGroup::class)];
        $attributes = $group->getAttributes();
        if (array_key_exists('status-message', $attributes)) {
            return $attributes['status-message']->value;
        }
        return '';
    }

    private function getGroupIndex(string $className): int
    {
        for ($i = 0; $i < sizeof($this->attributeGroups); $i++) {
            if ($this->attributeGroups[$i] instanceof $className) {
                return $i;
            }
        }
        $this->attributeGroups[] = new $className();
        return sizeof($this->attributeGroups) - 1;
    }

    /**
     * @return \Illuminate\Support\Collection<Printer>
     */
    public function getPrinters()
    {
        $printers = collect();
        foreach ($this->attributeGroups as $group) {
            if ($group instanceof \Rawilk\Printing\Api\Cups\Attributes\PrinterGroup) {
                $printers->push(new Printer($group->getAttributes()));
            }
        }
        return $printers;
    }

    /**
     * @return \Illuminate\Support\Collection<PrintJob>
     */
    public function getJobs()
    {
        $jobs = collect();
        foreach ($this->attributeGroups as $group) {
            if ($group instanceof \Rawilk\Printing\Api\Cups\Attributes\JobGroup) {
                $jobs->push(new PrintJob($group->getAttributes()));
            }
        }
        return $jobs;
    }
}