<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use Rawilk\Printing\Api\Cups\Exceptions\TypeNotSpecified;

abstract class AttributeGroup
{
    /**
     * Every attribute group has a specific delimiter tag
     *
     * @see https://www.rfc-editor.org/rfc/rfc2910#section-3.5
     */
    protected int $tag;

    /**
     * @var array<string, \Rawilk\Printing\Api\Cups\Type|array<int, \Rawilk\Printing\Api\Cups\Type>>
     */
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @var array<string, \Rawilk\Printing\Api\Cups\Type|array<int, \Rawilk\Printing\Api\Cups\Type>>
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    public function encode(): string
    {
        $binary = pack('c', $this->tag);
        foreach ($this->attributes as $name => $value) {
            if (gettype($value) === 'array') {
                $binary .= $this->handleArrayEncode($name, $value);

                continue;
            }
            if (! $value instanceof Type) {
                throw new TypeNotSpecified('Attribute value has to be of type ' . Type::class);
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
     *
     * @param  array<int, \Rawilk\Printing\Api\Cups\Type>  $values
     */
    private function handleArrayEncode(string $name, array $values): string
    {
        $str = '';
        if ($values[0] instanceof \Rawilk\Printing\Api\Cups\Types\RangeOfInteger) {
            \Rawilk\Printing\Api\Cups\Types\RangeOfInteger::checkOverlaps($values);
        }
        for ($i = 0; $i < count($values); $i++) {
            $_name = $name;
            if ($i !== 0) {
                $_name = '';
            }
            $nameLen = strlen($_name);

            $str .= pack('c', $values[$i]->getTag()); // Value tag
            $str .= pack('n', $nameLen); // Attribute key length
            $str .= pack('a' . $nameLen, $_name); // Attribute key

            $str .= $values[$i]->encode();
        }

        return $str;
    }
}
