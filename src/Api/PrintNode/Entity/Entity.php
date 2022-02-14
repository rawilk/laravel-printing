<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use ArrayAccess;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionObject;
use ReflectionProperty;

abstract class Entity implements Arrayable, JsonSerializable, ArrayAccess
{
    public function __construct(array $data = [])
    {
        $this->mapResponse($data);
    }

    protected function mapResponse(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } elseif (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    protected function getTimestamp($timestamp): null|Carbon
    {
        if (! is_string($timestamp)) {
            return null;
        }

        $date = Carbon::createFromFormat('Y-m-d\TH:i:s.v\Z', $timestamp);

        if ($date === false) {
            return null;
        }

        return $date;
    }

    public function toArray(): array
    {
        $publicProperties = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);

        return collect($publicProperties)
            ->mapWithKeys(function (ReflectionProperty $property) {
                return [$property->name => $this->{$property->name}];
            })->toArray();
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function offsetExists(mixed $offset): bool
    {
        return property_exists($this, $offset) && isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
        if (! property_exists($this, $offset)) {
            return null;
        }

        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (property_exists($this, $offset)) {
            $this->{$offset} = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        if (! property_exists($this, $offset)) {
            return;
        }

        $freshInstance = new static;
        $this->{$offset} = $freshInstance->{$offset};
    }
}
