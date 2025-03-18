---
title: Overview
sort: 1
---

## Introduction

[CUPS](https://www.cups.org/) is a modular printing system for unix-like computer operating systems which allows a computer to act as a print server. A computer running CUPS is a host that can accept print jobs from client computers, process them, and send them to the appropriate printer.

## Installation

You will need a computer capable or running CUPS on the same network as any printers you are going to print to.

### Step 1: Install CUPS

Installing and configuring CUPS is outside the scope of this documentation, however [this guide](https://www.techrepublic.com/videos/how-to-configure-a-print-server-with-ubuntu-server-cups-and-bonjour/) should be helpful in setting a CUPS server up.

If you know a better reference for this, please feel free to submit a PR with a link to it.

### Step 2: Set CUPS as your print driver

To use the CUPS driver, you need to configure the package to use it by default. This can be done by setting it in your `.env` file:

```bash
PRINTING_DRIVER=cups
```

You may also set it on specific requests like this:

```php
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Enums\PrintDriver;

Printing::driver(PrintDriver::Cups)->newPrintTask();
```

### Step 3: Configure CUPS

Enter the following credentials for your CUPS installation into your `.env` file:

```bash
CUPS_SERVER_IP=your-ip-address
CUPS_SERVER_USERNAME=your-username
CUPS_SERVER_PASSWORD=your-password
CUPS_SERVER_PORT=631 # This is the typical value
CUPS_SERVER_SECURE=false # true if using https
```

> {tip} The CUPS IP address should also work with a regular hostname as well (e.g., acme.com).

> {note} If you plan on setting any of these credentials globally through a service provider, you should omit them from your `.env` file.

#### Alternate Configuration Method

Most common in something like a multi-tenant setup where each tenant may have their own print server credentials, you may need to configure CUPS at runtime. As noted above, you should use all null values in your config in these scenarios.

```php
use Rawilk\Printing\Api\Cups\Cups;

Cups::setIp('your-ip');
Cups::setAuth('your-username', 'your-password');
Cups::setPort(631);
Cups::setSecure(true);
```

Configuration has been segmented like this to allow more flexibility in what needs to be set at runtime.
