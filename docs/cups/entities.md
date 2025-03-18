---
title: Entities
sort: 2
---

## Introduction

The `Printer` and `PrintJob` entities returned from the `CUPS` driver offer some additional functionalities to the interfaces they implement.

## Printer

`Rawilk\Printing\Drivers\Cups\Entity\Printer`

Here is a basic reference to the additional information provided by a CUPS Printer object. See [Printer](/docs/laravel-printing/{version}/basic-usage/printer) for more information about the base printer object.

### Methods

<hr>

#### id

_string_

The ID of a printer retrieved from CUPS will be a uri to the printer on your CUPS server.

<hr>

#### printer

_Rawilk\Printing\Api\Cups\Resources\Printer_

Returns an instance of the printer resource retrieved from CUPS.

<hr>

## PrintJob

`Rawilk\Printing\Drivers\Cups\Entity\PrintJob`

Here is a basic reference to the additional information provided by a CUPS PrintJob object. See [PrintJob](/docs/laravel-printing/{version}/basic-usage/print-job) for more information about the base print job object.

### Methods

<hr>

#### id

_string_

The ID of a print job retrieved from CUPS will be a uri to the job on your CUPS server.

#### job

_Rawilk\Printing\Api\Cups\Resources\PrintJob_

Returns an instance of the print job retrieved from CUPS.

<hr>
