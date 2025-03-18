---
title: Requirements
sort: 2
---

## General Requirements

-   PHP **8.1**<sup>1</sup> or greater
-   Laravel **8.0** or greater
-   A printer on your local network that you can print to and that your selected driver can access.
-   A receipt printer if you are printing receipts

<sup>1</sup> The package doesn't officially support PHP 8.0, but it should still run on that version.

## Driver Requirements

### PrintNode

-   A PrintNode account and api key.
-   A local computer/server that can run the [PrintNode client software](https://www.printnode.com/en/download) - this computer/server will need to be able to print to any printers you wish to use.

### CUPS

-   A local print server running CUPS **on the same network** as any printers you are going to print to. See [this guide](https://www.techrepublic.com/article/how-to-configure-a-print-server-with-ubuntu-server-cups-and-bonjour/) for help.

> {note} When using CUPS you can either use a local CUPS server that runs **on the same server as your Laravel installation** (useful for local development), or you can specify an IP address, username, and password for a remote CUPS server. The remote CUPS server **must be on the same network as any printers** you are going to print to.

## Version Matrix

| Laravel | Minimum Version | Maximum Version |
| ------- | --------------- |-----------------|
| 6.0     | 1.0.0           | 1.3.0           |
| 7.0     | 1.0.0           | 1.3.0           |
| 8.0     | 1.2.2           | 3.0.5           |
| 9.0     | 3.0.0           | 3.0.5           |
| 10.0    | 3.0.2           |                 |
| 11.0 | 3.0.4 | |
| 12.0 | 3.0.5 | |
