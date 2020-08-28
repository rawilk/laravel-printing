---
title: Printer
sort: 2
---

Each printer object should be an implementation of `Rawilk\Printing\Contracts\Printer`. The printer has several properties on it that can
be accessed via these methods:

## Printer Id
Your print server will create a unique id for each printer you have on it. You can retrieve the id like this:

```php
$printer->id()
```

## Printer Name
Each printer should also have a name, which can be retrieved like this:

```php
$printer->name()
```

## Capabilities
Your print server should be able to return a listing of the printer's capabilities. You can retrieve an array of them via:

```php
$printer->capabilities()
```

## Trays
If your printer and print driver support it, you can get a listing of your printer's available trays for use later:

```
php$printer->trays()
```

## Printer status
Your print server should return a text representation of your printer's current status:

```
php$printer->status()
```

You can also check if the printer is online via:

```
php$printer->isOnline()
```

## Description
If your printer has a description set on it, it can be retrieved via:

```
php$printer->status()
```

## Serialization
The printer object can also be cast to array or json, and it will return the following info:

- id
- name
- description
- online
- status
- trays
