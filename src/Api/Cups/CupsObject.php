<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\Cups;

use ArrayAccess;
use BackedEnum;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Rawilk\Printing\Api\Cups\Util\RequestOptions;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Printing;
use Rawilk\Printing\Util\Set;

/**
 * Represents a resource retrieved from a CUPS server.
 */
class CupsObject implements Arrayable, ArrayAccess, Countable, JsonSerializable
{
    use Macroable;

    protected RequestOptions $_opts;

    protected array $_values = [];

    public function __construct(
        ?string $uri = null,
        array|null|RequestOptions $opts = null,
    ) {
        if ($uri !== null) {
            $this->_values['uri'] = $uri;
        }

        $this->_opts = RequestOptions::parse($opts);
    }

    // region Magic
    public function __set(string $name, $value): void
    {
        // Convert camelCase back to kebab-case for the internal attribute keys.
        $name = Str::kebab($name);

        throw_if(
            static::getPermanentAttributes()->includes($name),
            InvalidArgument::class,
            "Cannot set {$name} on this object. HINT: you can't set: " .
            implode(', ', static::getPermanentAttributes()->toArray()),
        );

        $this->_values[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        // Convert camelCase back to kebab-case for the internal attribute keys.
        $name = Str::kebab($name);

        return isset($this->_values[$name]);
    }

    public function __unset(string $name): void
    {
        // Convert camelCase back to kebab-case for the internal attribute keys.
        $name = Str::kebab($name);

        unset($this->_values[$name]);
    }

    public function __debugInfo(): ?array
    {
        return $this->_values;
    }

    public function __toString(): string
    {
        $class = static::class;

        return $class . ' JSON: ' . $this->toJson();
    }
    // endregion

    public static function make(array $values, array|null|RequestOptions $opts = null): static
    {
        $obj = new static($values['uri'] ?? null);
        $obj->refreshFrom($values, $opts);

        return $obj;
    }

    /**
     * Attributes that are not updateable on the resource.
     */
    public static function getPermanentAttributes(): Set
    {
        static $permanentAttributes = null;
        if ($permanentAttributes === null) {
            $permanentAttributes = new Set([
                'id',
                'uri',
                'printer-uri-supported',
                'job-uri',
            ]);
        }

        return $permanentAttributes;
    }

    public function &__get(string $name)
    {
        // Attributes from CUPS are in kebab-case.
        $name = Str::kebab($name);

        // Function should return a reference, using $nullValue to return a reference to null.
        $nullValue = null;
        if (! empty($this->_values) && array_key_exists($name, $this->_values)) {
            return $this->_values[$name];
        }

        $class = $this::class;

        Printing::getLogger()?->error("CUPS notice: Undefined property of {$class} instance: {$name}");

        return $nullValue;
    }

    /**
     * Refresh this object using the provided values.
     */
    public function refreshFrom(array|self $values, array|null|RequestOptions $opts = null): void
    {
        $this->_opts = RequestOptions::parse($opts);

        if ($values instanceof self) {
            $values = $values->toArray();
        }

        $this->updateAttributes($values);
    }

    /**
     * Mass assign attributes on the object.
     */
    public function updateAttributes(array $values): void
    {
        $this->_values = $this->mutateAttributes($values);
    }

    public function keys(): array
    {
        return array_keys($this->_values);
    }

    public function values(): array
    {
        return array_values($this->_values);
    }

    public function toArray(): array
    {
        return $this->_values;
    }

    // region ArrayAccess
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->_values);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->_values[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Convert possible kebab-case to camelCase.
        $offset = Str::camel($offset);

        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        // Convert possible kebab-case to camelCase.
        $offset = Str::camel($offset);

        unset($this->{$offset});
    }
    // endregion

    public function count(): int
    {
        return count($this->_values);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    protected function mutateAttributes(array $values): array
    {
        return $values;
    }

    protected function attributeValue(array $values, string $attribute, mixed $default = null): mixed
    {
        if (! array_key_exists($attribute, $values)) {
            return $default;
        }

        $value = $values[$attribute];

        if (is_array($value)) {
            return array_map(fn($item) => $item->value, $value);
        }

        if ($value instanceof Type) {
            return $value->value;
        }

        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return $value;
    }
}
