---
title: Computer Service
sort: 5
---

## Introduction

The `ComputerService` can be used to fetch all computers associated with your PrintNode account. It can also be used to delete computers from your account.

All methods are callable from the `PrintNodeClient` class.

```php
$computers = $client->computers->all();
```

See the [API Overview](/docs/laravel-printing/{version}/printnode/api) for more information on interacting with the PrintNode API.

## Reference

### Methods
<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Computer>_

Retrieves all computers associated with your PrintNode account.

| param | type | default |
| --- | --- | --- |
| `$params` | array\|null | null |
| `$opts` | null\|array\|RequestOptions | null |

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\Computer_

Retrieve a computer from the API.

| param | type | default | description                    |
| --- | --- | --- |--------------------------------|
| `$id` | int | | the computer's ID              |
| `$params` | array\|null | null | not applicable to this request |
| `$opts` | null\|array\|RequestOptions | null |                                |

<hr>

#### retrieveSet

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Computer>_

Retrieve a specific set of computers.

| param     | type                        | default | description                          |
|-----------|-----------------------------| --- |--------------------------------------|
| `$ids`    | array                       | | the IDs of the computers to retrieve |
| `$params` | array\|null                 | null |                                  |
| `$opts`   | null\|array\|RequestOptions | null |                                      |

<hr>

#### delete

_array_

Delete a given computer. Method will return an array of affected computer IDs.

| param | type | default | description                    |
| --- | --- | --- |--------------------------------|
| `$id` | int | | the computer's ID              |
| `$params` | array\|null | null | not applicable to this request |
| `$opts` | null\|array\|RequestOptions | null |                                |

<hr>

#### deleteMany

_array_

Delete a set of computers. Omit or use an empty array of `$ids` to delete all computers. Method will return an array of affected IDs.

| param     | type                        | default | description                        |
|-----------|-----------------------------| --- |------------------------------------|
| `$ids`    | array                       | | the IDs of the computers to delete |
| `$params` | array\|null                 | null |                                    |
| `$opts`   | null\|array\|RequestOptions | null |                                    |

<hr>

#### printers

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Retrieve all printers attached to a given computer.

| param       | type                        | default | description                                                                  |
|-------------|-----------------------------|-------|------------------------------------------------------------------------------|
| `$parentId` | int\|array                  | | the computer's ID. pass an array to retrieve printers for multiple computers |
| `$params`   | array\|null                 | null  |                                                                              |
| `$opts`     | null\|array\|RequestOptions | null  |                                                                              |

<hr>

#### printer

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Retrieve one or many printers attached to a given computer.

| param       | type                        | default | description                                                                  |
|-------------|-----------------------------|--------|------------------------------------------------------------------------------|
| `$parentId` | int\|array                  | | the computer's ID. pass an array to retrieve printers for multiple computers |
| `$printerId` | int\|array | | the printer's ID. pass an array to retrieve a set of printers                |
| `$params`   | array\|null                 | null   |                                                                              |
| `$opts`     | null\|array\|RequestOptions | null   |                                                                              |

<hr>

## Computer Resource

`Rawilk\Printing\Api\PrintNode\Resources\Computer`

A computer represents a device that has the PrintNode Client software installed on it, and which has successfully connected to PrintNode. When the PrintNode Client runs on a computer it automatically reports the existence of the computer to the server. From then on the computer is recognized by the API.

### Properties
<hr>

#### id

_int_

The computer's ID.

<hr>

#### createTimestamp

_string_

Time and date the computer was first registered with PrintNode.

<hr>

#### name

_string_

The computer's name.

<hr>

#### state

_string_

Current state of the computer.

<hr>

#### hostname

_?string_

The computer's host name.

<hr>

#### inet

_?string_

The computer's ipv4 address.

<hr>

#### inet6

_?string_

The computer's ivp6 address.

<hr>

#### jre

_?string_

Reserved.

<hr>

#### version

_?string_

The PrintNode software version that is run on the computer.

<hr>

### Methods
<hr>

#### createdAt

_?CarbonInterface_

A date object representing the time and date the computer was first registered with PrintNode.

<hr>

#### printers

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Printer>_

Fetch all printers attached to the computer.

```php
$printers = $computer->printers();
```

| param | type | default |
| --- | --- |---------|
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

#### findPrinter

_Rawilk\Printing\Api\PrintNode\Resources\Printer_

Find a specific printer attached to the printer. Pass an array for `$id` to find a set of printers.

```php
$printer = $computer->findPrinter(100);
```

| param | type | default |
| --- | --- |---------|
| `$id` | int\|array | |
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

#### delete

_Rawilk\Printing\Api\PrintNode\Resources\Computer_

Delete the computer instance.

| param | type | default |
| --- | --- |---------|
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

### Static Methods
<hr>

#### all

_Collection<int, Rawilk\Printing\Api\PrintNode\Resources\Computer>_

Retrieve all computers.

```php
Computer::all();
```

| param | type | default |
| --- | --- |---------|
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |

<hr>

#### retrieve

_Rawilk\Printing\Api\PrintNode\Resources\Computer_

Retrieve a computer with a given id.

```php
$computer = Computer::retrieve(100);
```

| param | type | default |
| --- | --- |---------|
| `$id` | int | |
| `$params` | null\|array | null    |
| `$opts` | null\|array\|RequestOptions | null    |
