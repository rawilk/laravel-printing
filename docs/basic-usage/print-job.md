---
title: PrintJob
sort: 3
---

## Introduction

Each print job object returned from a `Driver` should be an implementation of `Rawilk\Printing\Contracts\PrintJob`. A print job represents a job that was sent to a physical printer on a print server.

## Reference

`Rawilk\Printing\Contracts\PrintJob`

### Methods
<hr>

#### date

_?CarbonInterface_

The date the job was created.

<hr>

#### id

_int|string_

The ID of the job. Some drivers like `CUPS` may return a uri to the job instead.

<hr>

#### name

_?string_

If reported by the driver, the name of the print job.

<hr>

#### printerId

_int|string|mixed_

If reported by the driver, the id of the printer the job was sent to. Some drivers like `CUPS` will give a uri to the printer instead.

<hr>

#### printerName

_?string_

If reported by the driver, the name of the printer the job was sent to.

<hr>

#### state

_?string_

The reported status of the job.

<hr>

## Serialization

The print job object can also be cast to array or json, and it will return the following info:

-   id
-   date
-   name
-   printerId
-   printerName
-   state

> {note} Some drivers may serialize this slightly different.
