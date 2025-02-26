<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Attributes\JobGroup;
use Rawilk\Printing\Api\Cups\Attributes\OperationGroup;
use Rawilk\Printing\Api\Cups\Enums\AttributeGroupTag;
use Rawilk\Printing\Api\Cups\Enums\Operation;
use Rawilk\Printing\Api\Cups\Enums\Version;
use Rawilk\Printing\Api\Cups\Types\Charset;
use Rawilk\Printing\Api\Cups\Types\NaturalLanguage;

class Request
{
    protected Version $version;

    protected int $operation;

    protected int $requestId = 1;

    protected string $content = '';

    /**
     * @var \Rawilk\Printing\Api\Cups\AttributeGroup[]
     */
    protected array $attributeGroups = [];

    public function __construct()
    {
        $this->addOperationAttributes([
            'attributes-charset' => new Charset('utf-8'),
            'attributes-natural-language' => new NaturalLanguage('en'),
        ]);
    }

    public function setVersion(Version $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function setOperation(int|Operation $operation): static
    {
        $this->operation = $operation instanceof Operation ? $operation->value : $operation;

        return $this;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * You may optionally specify the request ID, default is 1
     */
    public function setRequestId(int $requestId): static
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @param  array<string, \Rawilk\Printing\Api\Cups\Type|\Rawilk\Printing\Api\Cups\Type[]>  $attributes
     */
    public function addOperationAttributes(array $attributes): static
    {
        $this->setAttributes(OperationGroup::class, $attributes);

        return $this;
    }

    /**
     * @param  array<string, \Rawilk\Printing\Api\Cups\Type|\Rawilk\Printing\Api\Cups\Type[]>  $attributes
     */
    public function addJobAttributes(array $attributes): static
    {
        $this->setAttributes(JobGroup::class, $attributes);

        return $this;
    }

    public function encode(): string
    {
        $binary = $this->version->encode();
        $binary .= pack('n', $this->operation);
        $binary .= pack('N', $this->requestId);

        foreach ($this->attributeGroups as $group) {
            $binary .= $group->encode();
        }

        $binary .= pack('c', AttributeGroupTag::EndOfAttributes->value);

        if ($this->content) {
            $binary .= $this->content;
        }

        return $binary;
    }

    protected function setAttributes(string $className, array $attributes): void
    {
        $index = $this->getGroupIndex($className);

        foreach ($attributes as $name => $value) {
            $this->attributeGroups[$index]->{$name} = $value;
        }
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
