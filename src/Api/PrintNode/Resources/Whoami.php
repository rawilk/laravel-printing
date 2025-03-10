<?php

declare(strict_types=1);

namespace Rawilk\Printing\Api\PrintNode\Resources;

use Rawilk\Printing\Api\PrintNode\PrintNodeApiResource;

/**
 * The `Whoami` object represents the account information
 * related to a given API key.
 *
 * @property int $id The account's ID
 * @property string $firstname The account holder's first name
 * @property string $lastname The account holder's last name
 * @property string $email The account holder's email address
 * @property bool $canCreateSubAccounts Determines if this account can create sub-accounts, e.g. you have an integrator account. <a href="https://api.printnode.com/app/integrators/upgrade">Upgrade account link</a>
 * @property null|string $creatorEmail The email address of the account that created this sub-account
 * @property null|string $creatorRef The creation reference set when the account was created
 * @property array $childAccounts Any child accounts present on this account
 * @property int|null $credits The number of print credits remaining on this account
 * @property int $numComputers The number of computers active on this account
 * @property int $totalPrints Total number of prints made on this account
 * @property array $versions A collection of versions set on this account
 * @property array $connected A collection of computer IDs signed in on this account
 * @property array $Tags A collection of tags set on this account
 * @property array $ApiKeys A collection of al the api keys set on this account
 * @property string $state The status of the account
 * @property array $permissions The permissions set on this account
 */
class Whoami extends PrintNodeApiResource
{
    public static function classUrl(): string
    {
        return '/whoami';
    }

    public static function resourceUrl(?int $id = null): string
    {
        return static::classUrl();
    }

    /**
     * Indicates if the account is considered active.
     */
    public function isActive(): bool
    {
        return $this->_values['state'] === 'active';
    }
}
