---
title: Overview
sort: 1
---

## Introduction

[PrintNode](https://printnode.com) is a cloud printing service which allows you to connect any printer to your application using a PrintNode Client and a JSON API.

## Installation

Follow these steps to sign up for an account and get started printing using the PrintNode API.

### Step 1: Sign Up

Before you can use the API, you will need to sign up for a PrintNode account, and make a new API key. You can sign up here: https://app.printnode.com/account/register

### Step 2: Add a computer and printer

To have somewhere to print to you need to download and install the PrintNode desktop client on a computer with some printers. You can download the PrintNode Client installer here - https://printnode.com/download.

Setup should be pretty straightforward, however more detailed instructions can be found here if necessary - https://printnode.com/docs/installation/windows.

### Step 3: Configure your api key

To access the PrintNode api, you need to configure the package to use it. The easiest way to do this is by adding the following to your `.env` file:

```bash
PRINT_NODE_API_KEY=your-api-key
```

#### Alternate Configuration

Most common in something like a multi-tenant setup where each tenant may have their own api credentials, you may configure PrintNode at runtime. In these scenarios, you should omit the api key from your `.env` file or set the value to `null`.

```php
use Rawilk\Printing\Api\PrintNode\PrintNode;

PrintNode::setApiKey('your-api-key');
```

### Step 4: Set PrintNode as your print driver

To use the PrintNode driver, you need to configure the package to use it by default. This can be done by setting it in your `.env` file:

```bash
PRINTING_DRIVER=printnode
```

You may also use it on specific requests like this:

```php
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Enums\PrintDriver;

Printing::driver(PrintDriver::PrintNode)->newPrintTask();
```
