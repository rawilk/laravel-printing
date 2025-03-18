<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Traits\Macroable;
use JsonSerializable;
use Rawilk\Printing\Api\PrintNode\Util\RequestOptions;
use Rawilk\Printing\Api\PrintNode\Util\Util;
use Rawilk\Printing\Exceptions\InvalidArgument;
use Rawilk\Printing\Printing;
use Rawilk\Printing\Util\Set;

/**
 * Represents some kind of resource retrieved from the PrintNode API.
 */
class PrintNodeObject implements Arrayable, ArrayAccess, Countable, JsonSerializable
{
    use Macroable;

    protected RequestOptions $_opts;

    protected array $_values = [];

    protected ?PrintNodeApiResponse $_lastResponse = null;

    public function __construct(
        null|int|string|array $id = null,
        array|null|RequestOptions $opts = null,
    ) {
        [$id] = Util::normalizeId($id);

        if ($id !== null) {
            $this->_values['id'] = $id;
        }

        $this->_opts = RequestOptions::parse($opts);
    }

    // region Magic
    public function __set(string $name, $value): void
    {
        throw_if(
            static::getPermanentAttributes()->includes($name),
            new InvalidArgument(
                "Cannot set {$name} on this object. HINT: you can't set: " .
                implode(', ', static::getPermanentAttributes()->toArray()),
            ),
        );

        $this->_values[$name] = Util::convertToPrintNodeObject($value, $this->_opts, static::class);
    }

    public function __isset(string $name): bool
    {
        return isset($this->_values[$name]);
    }

    public function __unset(string $name): void
    {
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
        $obj = new static($values['id'] ?? null);
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
            ]);
        }

        return $permanentAttributes;
    }

    public function &__get(string $name)
    {
        // Function should return a reference, using $nullValue to return a reference to null.
        $nullValue = null;
        if (! empty($this->_values) && array_key_exists($name, $this->_values)) {
            return $this->_values[$name];
        }

        $class = $this::class;

        Printing::getLogger()?->error("PrintNode notice: Undefined property of {$class} instance: {$name}");

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
        foreach ($values as $key => $value) {
            $this->_values[$key] = Util::convertToPrintNodeObject(
                $value,
                $this->_opts,
                $this->getExpectedValueResource($key),
            );
        }
    }

    public function keys(): array
    {
        return array_keys($this->_values);
    }

    public function values(): array
    {
        return array_values($this->_values);
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
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }
    // endregion

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $maybeToArray = function (mixed $value) {
            if ($value === null) {
                return null;
            }

            return is_object($value) && method_exists($value, 'toArray') ? $value->toArray() : $value;
        };

        return array_reduce(
            array_keys($this->_values),
            function ($carry, $key) use ($maybeToArray): array {
                if (str_starts_with((string) $key, '_')) {
                    return $carry;
                }

                $value = $this->_values[$key];
                if (Util::isList($value)) {
                    $carry[$key] = array_map($maybeToArray, $value);
                } else {
                    $carry[$key] = $maybeToArray($value);
                }

                return $carry;
            },
            [],
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }

    public function count(): int
    {
        return count($this->_values);
    }

    /**
     * @return null|\Rawilk\Printing\Api\PrintNode\PrintNodeApiResponse The last response from the PrintNode API
     */
    public function getLastResponse(): ?PrintNodeApiResponse
    {
        return $this->_lastResponse;
    }

    /**
     * Set the last response from the PrintNode API.
     */
    public function setLastResponse(?PrintNodeApiResponse $response): void
    {
        $this->_lastResponse = $response;
    }

    /**
     * Retrieve the expected api resource for a given key.
     */
    protected function getExpectedValueResource(string $key): ?string
    {
        return null;
    }
}
