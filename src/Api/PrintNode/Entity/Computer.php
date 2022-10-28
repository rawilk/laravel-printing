<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Carbon\Carbon;

class Computer extends Entity
{
    public string|int $id;

    public ?string $name = null;

    public ?string $hostName = null;

    public ?string $state = null;

    public ?string $inet = null;

    public ?string $inet6 = null;

    public ?string $version = null;

    public ?string $jre = null;

    public ?Carbon $created = null;

    public function setHostName(?string $hostName): self
    {
        $this->hostName = $hostName;

        return $this;
    }

    public function setCreateTimestamp($timestamp): self
    {
        $this->created = $this->getTimestamp($timestamp);

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'createTimestamp' => $this->created,
        ]);
    }
}
