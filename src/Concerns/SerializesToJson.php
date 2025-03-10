<?php

declare(strict_types=1);

namespace Rawilk\Printing\Concerns;

trait SerializesToJson
{
    public function __toString(): string
    {
        $class = static::class;

        return $class . ' JSON: ' . $this->toJson();
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
