---
title: PrintNode API
sort: 6
---

## Introduction

If you use the PrintNode driver and need more flexibility than what you get with the `Printing` facade, you may interact with the API wrapper directly.
The easiest way to do this is by resolving it out of the container:

```php
$api = app(\Rawilk\Printing\Api\PrintNode\PrintNodeClientTemp::class);
```

The api class automatically receives your api key from the config, but if you need to change it on the fly, you can do it like this:

```php
app(\Rawilk\Printing\Api\PrintNode\PrintNodeClientTemp::class)->setApiKey('your-new-key');
```

Doing this should work even if you are using the `Printing` facade to interact with the api.

There are a few extra things you may utilize with this API wrapper class, which are listed below. More methods may be added in the future as well.

## Whoami

You can use this to find out the account info that is related to your configured api key. It can also be useful just to be sure your api requests
are actually working as well.

```php
$whoami = $api->whoami();

$whoami->id;
$whoami->firstName;
$whoami->lastName;
$whoami->email;
```

## Computers

If you are looking to list out an account's registered computers, you may use this method:

```php
// $limit, $offset and $dir are optional params used for pagination.
// You should refer to PrintNode's api docs for more info on them.
$response = $api->computers($limit, $offset, $dir);

$response->computers; // a collection of `\Rawilk\Printing\Api\PrintNode\Entity\Computer` instances
```

## Computer

If you know the ID of a computer you want to find, you may use this method:

```php
$computer = $api->computer(1234);

$computer->id;
$computer->name;
$computer->hostName;
$computer->state;
$computer->created; // Carbon instance of date computer was created on your account
```

## Other Methods

Full a full reference of methods, please refer to API class. Since the API class is `Macroable`, you may add any additional functionality you need to this
class via a service provider.
