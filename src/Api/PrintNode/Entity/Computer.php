<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

use Carbon\Carbon;

class Computer extends Entity
{
    public string|int $id;
    public null|string $name = null;
    public null|string $hostName = null;
    public null|string $state = null;
    public null|string $inet = null;
    public null|string $inet6 = null;
    public null|string $version = null;
    public null|string $jre = null;
    public null|Carbon $created = null;

    public function setHostName(null|string $hostName): self
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
