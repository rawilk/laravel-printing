<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionObject;
use ReflectionProperty;

abstract class Entity implements Arrayable, JsonSerializable
{
    public function __construct(array $data = [])
    {
        $this->mapResponse($data);
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

    protected function getTimestamp($timestamp): ?Carbon
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
}
