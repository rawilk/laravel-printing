<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

abstract class AttributeGroup
{
    /**
     * Every attribute group has a specific delimiter tag
     *
     * @see https://www.rfc-editor.org/rfc/rfc2910#section-3.5
     */
    protected int $tag;

    /**
     * @var array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    protected array $attributes = [];

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @var array<string, \Rawilk\Printing\Api\Cups\Type>
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function encode(): string
    {
        $binary = pack('c', $this->tag);
        foreach ($this->attributes as $name => $value) {
            if (gettype($value->value) === 'array') {
                $binary .= $this->handleArrayEncode($name, $value);
                continue;
            }
            $nameLen = strlen($name);

            $binary .= pack('c', $value->getTag());
            $binary .= pack('n', $nameLen); // Attribute key length
            $binary .= pack('a' . $nameLen, $name); // Attribute key
            $binary .= $value->encode();  // Attribute value (with length)
        }
        return $binary;
    }

    /**
     * If attribute is an array, the attribute name after the first element is empty
     */
    private function handleArrayEncode(string $name, \Rawilk\Printing\Api\Cups\Type $value): string
    {
        $str = '';
        for ($i = 0; $i < sizeof($value->value); $i++) {
            $_name = $name;
            if ($i !== 0) {
                $_name = '';
            }
            $nameLen = strlen($_name);

            $str .= pack('c', $value->getTag()); // Value tag
            $str .= pack('n', $nameLen); // Attribute key length
            $str .= pack('a' . $nameLen, $_name); // Attribute key

            $class = $value::class;
            $str .= (new $class($value->value[$i]))->encode();
        }
        return $str;
    }
}
