---
title: Whoami Service
sort: 8
---

## Introduction

The `WhoamiService` can be used to fetch the account information of your PrintNode account. It can also be used to verify your api key is working for api requests to PrintNode.

All methods are callable from the `PrintNodeClient` class.

```php
$whoami = $client->whoami->check();
```

See the [API Overview](/docs/laravel-printing/{version}/printnode/api) for more information on interacting with the PrintNode API.

## Reference

### Methods
<hr>

#### check

_Rawilk\Printing\Api\PrintNode\Resources\Whoami_

Retrieve the account information based on the current api key.

| param | type | default |
| --- | --- | --- | 
| `$opts` | null\|array\|RequestOptions | null |

<hr>

## Whoami Resource

`Rawilk\Printing\Api\PrintNode\Resources\Whoami`

The `Whoami` object represents the account information related to a given API key.

### Properties
<hr>

#### id

_int_

The account's ID.

<hr>

#### firstname

_string_

The account holder's first name.

<hr>

#### lastname

_string_

The account holder's last name.

<hr>

#### email

_string_

The account holder's email address.

<hr>

#### canCreateSubAccounts

_bool_

Indicates if this account can create sub-accounts, e.g., you have an integrator account.

<hr>

#### creatorEmail

_?string_

The email address of the account that created this sub-account.

<hr>

#### creatorRef

_?string_

The creation reference set when the sub-account was created.

<hr>

#### childAccounts

_array_

Any child accounts present on this account.

<hr>

#### credits

_?int_

The number of print credits remaining on this account.

<hr>

#### numComputers

_int_

The number of computers active on this account.

<hr>

#### totalPrints

_int_

Total number of prints made on this account.

<hr>

#### versions

_array_

A collection of versions set on this account.

<hr>

#### connected

_array_

A collection of computer IDs signed in on this account.

<hr>

#### Tags

_array_

A collection of tags set on this account.

<hr>

#### ApiKeys

_array_

A collection of all the api keys set on this account.

<hr>

#### state

_string_

The status of the account.

<hr>

#### permissions

_array_

The permissions set on this account.

<hr>

### Methods
<hr>

#### isActive

_bool_

Indicates `true` if the account is considered active.

<hr>
