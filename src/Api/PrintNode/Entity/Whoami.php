<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Entity;

class Whoami extends Entity
{
    public string|int $id;
    public null|string $firstName = null;
    public null|string $lastName = null;
    public null|string $email = null;
    public bool $canCreateSubAccounts = false;
    public null|string $creatorEmail = null;
    public null|string $creatorRef = null;
    public array $childAccounts = [];
    public null|int|string $credits = null;
    public string|int $numComputers = 0;
    public string|int $totalPrints = 0;
    public array $versions = [];
    public array $apiKeys = [];
    public array $tags = [];
    public array $connected = [];
    public null|string $state = null;
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
