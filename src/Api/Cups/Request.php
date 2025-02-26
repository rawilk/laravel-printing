<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Types\Charset;
use Rawilk\Printing\Api\Cups\Types\NaturalLanguage;

class Request
{
    private Version $version;

    private int $operation;

    private int $requestId = 1;

    private string $content = '';

    /**
     * @var \Rawilk\Printing\Api\Cups\AttributeGroup[]
     */
    private array $attributeGroups = [];

    public function __construct()
    {
        $this->addOperationAttributes(
            [
                'attributes-charset' => new Charset('utf-8'),
                'attributes-natural-language' => new NaturalLanguage('en'),
            ]
        );
    }

    public function setVersion(Version $version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @see \Rawilk\Printing\Api\Cups\Operation Operations supported
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Set file contents to print
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * You may optionally specify the request ID, default is 1
     */
    public function setRequestId(int $requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @param array<string, \Rawilk\Printing\Api\Cups\Type|\Rawilk\Printing\Api\Cups\Type[]>> $attributes
     */
    public function addOperationAttributes(array $attributes)
    {
        $this->setAttributes(\Rawilk\Printing\Api\Cups\Attributes\OperationGroup::class, $attributes);

        return $this;
    }

    /**
     * @param  array<string, \Rawilk\Printing\Api\Cups\Type|\Rawilk\Printing\Api\Cups\Type[]>  $attributes
     */
    public function addJobAttributes(array $attributes)
    {
        $this->setAttributes(\Rawilk\Printing\Api\Cups\Attributes\JobGroup::class, $attributes);

        return $this;
    }

    public function encode()
    {

        $binary = $this->version->encode();
        $binary .= pack('n', $this->operation);
        $binary .= pack('N', $this->requestId);

        foreach ($this->attributeGroups as $group) {
            $binary .= $group->encode();
        }
        $binary .= pack('c', AttributeGroupTag::END_OF_ATTRIBUTES->value);

        if ($this->content) {
            $binary .= $this->content;
        }

        return $binary;
    }

    private function setAttributes(string $className, array $attributes)
    {
        $index = $this->getGroupIndex($className);
        foreach ($attributes as $name => $value) {
            $this->attributeGroups[$index]->$name = $value;
        }
    }

    private function getGroupIndex(string $className): int
    {
        for ($i = 0; $i < count($this->attributeGroups); $i++) {
            if ($this->attributeGroups[$i] instanceof $className) {
                return $i;
            }
        }
        $this->attributeGroups[] = new $className;

        return count($this->attributeGroups) - 1;
    }
}
