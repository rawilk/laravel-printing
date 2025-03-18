---
title: Printer
sort: 2
---

## Introduction

Each printer object returned from a `Driver` should be an implementation of `Rawilk\Printing\Contracts\Printer`. A printer represents a physical printer on your print server.

## Reference

`Rawilk\Printing\Contracts\Printer`

### Methods
<hr>

#### id

_string|int_

A print server typically assigns some kind of id or uri for a printer. For example, `CUPS` will return the uri to the printer.

<hr>

#### name 

_?string_

If reported by the driver, the printer's name.

<hr>

#### description

_?string_

If reported by the driver, a brief description of the printer.

<hr>

#### capabilities

_array_

If reported by the driver, this should be an array of the printer's capabilities (e.g., trays, collation, etc.)

<hr>

#### trays

_array_

If your printer and print driver support it, you can get a listing of your printer's available trays for use later.

<hr>

#### status

_string_

The printer's current reported status.

<hr>

#### isOnline

_bool_

Indicates if the printer has reported itself to be online.

<hr>

## Serialization

The printer object can also be cast to array or json, and it will return the following info:

-   id
-   name
-   description
-   online
-   status
-   trays (If supported by the driver)
-   capabilities (If supported by the driver)

> {note} Some drivers may serialize this slightly different.
