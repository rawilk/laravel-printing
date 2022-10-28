<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

class Whoami extends Entity
{
    public string|int $id;

    public ?string $firstName = null;

    public ?string $lastName = null;

    public ?string $email = null;

    public bool $canCreateSubAccounts = false;

    public ?string $creatorEmail = null;

    public ?string $creatorRef = null;

    public array $childAccounts = [];

    public null|int|string $credits = null;

    public string|int $numComputers = 0;

    public string|int $totalPrints = 0;

    public array $versions = [];

    public array $apiKeys = [];

    public array $tags = [];

    public array $connected = [];

    public ?string $state = null;

    public array $permissions = [];

    public function setFirstName(string $name): self
    {
        $this->firstName = $name;

        return $this;
    }

    public function setLastName(string $name): self
    {
        $this->lastName = $name;

        return $this;
    }

    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function setApiKeys(array $keys): self
    {
        $this->apiKeys = $keys;

        return $this;
    }
}
