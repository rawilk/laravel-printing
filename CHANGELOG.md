# Changelog

All notable changes to `laravel-printing` will be documented in this file.

## v4.0.0-beta.1 - 2025-03-18

This release is a pre-release! It is considered mostly stable, however breaking changes may possibly be introduced before a stable 4.x release is published, however I will do my best to prevent breaking changes as bugs are discovered and patched in this major version.

### What's Changed

- Cups by @vatsake in https://github.com/rawilk/laravel-printing/pull/92
- Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/rawilk/laravel-printing/pull/101
- [Release] 4.x by @rawilk in https://github.com/rawilk/laravel-printing/pull/99
- Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/100

### New Contributors

- @vatsake made their first contribution in https://github.com/rawilk/laravel-printing/pull/92

### Breaking Changes

- Drop Laravel 8 & 9 support
- Drop PHP 8.0 support
- Drop PHP 8.1 support
- `printing.factory` singleton renamed to `\Rawilk\Printing\Factory::class`
- `printing.driver` singleton renamed to `\Rawilk\Printing\Contracts\Driver::class`
- Remove `Cups` api singleton
- Remove `PrintNode` api singleton
- Rename `PrintNode` api class to `PrintNodeClient`
- PrintNode API `Entity` classes are now namespaced as `Resources`
- PrintNode API collection classes like `Computers` and `Printers` are removed in favor of default Laravel collections
- Convert `Rawilk\Printing\Drivers\PrintNode\ContentType` to enum and move to `Rawilk\Printing\Api\PrintNode\Enums` namespace
- Change `ContentType` casing to `PascalCase`
- Change method signature to retrieve `jobs()` on `Rawilk\Printing\Drivers\PrintNode\Entity\Printer`
- Force `Rawilk\Printing\Contracts\Printer` interface to use `Arrayable` and `JsonSerializable`
- Force `Rawilk\Printing\Contracts\PrintJob` interface to use `Arrayable` and `JsonSerializable`

### Other Changes

- Use `Str::random()` instead of `uniqid` when generating print job names
- Add new `PrintDriver` enum
- Add logging (configurable through .env through `PRINTING_LOGGER`)
- Add base `PrintingException` and have most of the package exceptions extend it
- Add `ExceptionInterface` contract that all package exceptions implement
- Add `PrintJobState` service and resource to the PrintNode API

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.5...v4.0.0-beta.1

## v3.0.5 - 2025-02-26

### What's Changed

- Bump aglipanci/laravel-pint-action from 2.3.1 to 2.4 by @dependabot in https://github.com/rawilk/laravel-printing/pull/90
- Bump dependabot/fetch-metadata from 1.6.0 to 2.2.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/94
- Laravel 12.x Compatibility by @laravel-shift in https://github.com/rawilk/laravel-printing/pull/97
- Add PHP 8.4 Compatibility by @rawilk in https://github.com/rawilk/laravel-printing/pull/98

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.4...v3.0.5

## v3.0.4 - 2024-03-10

### What's Changed

- Bump actions/stale from 5 to 8 by @dependabot in https://github.com/rawilk/laravel-printing/pull/58
- Bump dependabot/fetch-metadata from 1.3.6 to 1.4.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/59
- Bump dependabot/fetch-metadata from 1.4.0 to 1.5.1 by @dependabot in https://github.com/rawilk/laravel-printing/pull/60
- Bump dependabot/fetch-metadata from 1.5.1 to 1.6.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/68
- Update basic-usage.md by @vanrijs in https://github.com/rawilk/laravel-printing/pull/78
- Bump stefanzweifel/git-auto-commit-action from 4 to 5 by @dependabot in https://github.com/rawilk/laravel-printing/pull/75
- Bump aglipanci/laravel-pint-action from 2.2.0 to 2.3.1 by @dependabot in https://github.com/rawilk/laravel-printing/pull/80
- Laravel 11.x Compatibility by @laravel-shift in https://github.com/rawilk/laravel-printing/pull/84
- Add php 8.3 support by @rawilk in https://github.com/rawilk/laravel-printing/pull/85
- Chore: Update Pint Config by @rawilk in https://github.com/rawilk/laravel-printing/pull/86

### New Contributors

- @vanrijs made their first contribution in https://github.com/rawilk/laravel-printing/pull/78
- @laravel-shift made their first contribution in https://github.com/rawilk/laravel-printing/pull/84

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.3...v3.0.4

## v3.0.3 - 2023-03-20

### What's Changed

- Bump creyD/prettier_action from 4.2 to 4.3 by @dependabot in https://github.com/rawilk/laravel-printing/pull/55
- Bump aglipanci/laravel-pint-action from 1.0.0 to 2.2.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/56
- Add Php 8.2 compatibility by @rawilk in https://github.com/rawilk/laravel-printing/pull/57

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.2...v3.0.3

## v3.0.2 - 2023-02-15

### What's Changed

- Bump dependabot/fetch-metadata from 1.3.4 to 1.3.5 by @dependabot in https://github.com/rawilk/laravel-printing/pull/41
- Bump dependabot/fetch-metadata from 1.3.5 to 1.3.6 by @dependabot in https://github.com/rawilk/laravel-printing/pull/51
- Laravel 10.x compatiblity by @rawilk in https://github.com/rawilk/laravel-printing/pull/54

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.1...v3.0.2

## v3.0.1 - 2022-10-31

### Changed

- PHPUnit to Pest Converter by @rawilk in https://github.com/rawilk/laravel-printing/pull/31
- Bump creyD/prettier_action from 3.0 to 4.2 by @dependabot in https://github.com/rawilk/laravel-printing/pull/38
- Bump actions/checkout from 2 to 3 by @dependabot in https://github.com/rawilk/laravel-printing/pull/39
- Composer: Update mike42/escpos-php requirement from ^3.0 to ^4.0 by @dependabot in https://github.com/rawilk/laravel-printing/pull/40
- Update formatting throughout src
- Use `spatie/laravel-package-tools` for service provider
- Drop official support of PHP 8.0, however it should still run on that version

**Full Changelog**: https://github.com/rawilk/laravel-printing/compare/v3.0.0...v3.0.1

## 3.0.0 - 2022-02-15

### Added

- Add driver method for retrieving print jobs (**Breaking Change** to driver contract)
- Add driver method for retrieving a specific print job (**Breaking Change** to driver contract)
- Add driver method for retrieving a specific printer's print jobs (**Breaking Change** to driver contract)
- Add driver method for retrieving a specific print job on a specific printer (**Breaking Change** to driver contract)
- Add `printer()` method on PrintNode driver printer to access underlying PrintNode printer instance
- Add `job()` method on PrintNode driver print job to access underlying PrintNode print job instance
- Add a `printer` property on the PrintNode driver PrintJob class to access the printer instance

### Changed

- **Breaking Change:** Rename driver method `find()` to `printer()` for finding a specific printer
- **Breaking Change:** Add required `$limit`, `$offset`, and `$dir` pagination params to driver `printers()` method
- **Breaking Change:** Add `null|Carbon` return type to `PrintJob` contract `date()` method signature
- Write our own internal api wrapper for PrintNode driver instead of relying on package `printnode/printnode-php` (available via `app(\Rawilk\Printing\Api\PrintNode\PrintNode::class)`)
- Make `\Rawilk\Printing\Printing` macroable
- Make `Rawilk\Printing\PrintTask` macroable
- Make `Rawilk\Printing\Drivers\PrintNode\PrintNode` macroable
- Make `Rawilk\Printing\Drivers\Cups\Cups` macroable
- Make each concrete instance of `\Rawilk\Printing\Contracts\Printer` and `\Rawilk\Printing\Contracts\PrintJob` macroable
- Make `\Rawilk\Printing\Receipts\ReceiptPrinter` macroable

### Fixed

- Make `\Rawilk\Printing\Drivers\PrintNode\Entity\Printer` compatible with implemented `JsonSerializable` interface
- Return a given PrintNode driver printer instance's jobs via the `jobs()` method

### Updated

- Add support for Printnode PDF_Base64 ContentType ([#23](https://github.com/rawilk/laravel-printing/pull/23))

## 2.0.0 - 2021-01-11

### Updated

- Add support for php 8
- Drop support for php 7
- Drop support for Laravel 6
- Drop support for Laravel 7
- Remove driver dependencies from always being required
- Require user to pull in the driver dependencies for their drivers now

## 1.3.0 - 2020-09-13

### Added

- Add support for custom drivers
- Add support for changing print drivers on the fly

## 1.2.2 - 2020-09-08

### Added

- Add support for Laravel 8

## 1.2.1 - 2020-09-04

### Fixed

- Fix page range issue with CUPS driver ([#3](https://github.com/rawilk/laravel-printing/issues/3)).

## 1.2.0 - 2020-09-02

### Added

- Add support for CUPS driver.

## 1.1.6 - 2020-07-23

### Changed

- Remove `int` parameter type hint on `PrintNodePrintJob` `id` setter.

## 1.1.5 - 2020-07-22

### Fixed

- Ensure `str_repeat` gets repeated at least once to avoid fatal error on `twoColumnText`.

## 1.1.4 - 2020-07-15

### Fixed

- Return the job id of a new print job with PrintNode ([#1](https://github.com/rawilk/laravel-printing/issues/1)).

## 1.1.3 - 2020-07-09

### Changed

- Add return type `string` to `id()` method on PrintNode Printer.
- Add more method doc blocks to `ReceiptPrinter` for type hinting to underlying printer class.

## 1.1.2 - 2020-07-08

### Fixed

- Fix strict type comparison when finding a printer with PrintNode driver.

## 1.1.1 - 2020-07-08

### Changed

- Add method doc blocks to `Printing` facade for `defaultPrinterId()` and `defaultPrinter()`.

## 1.1.0 - 2020-07-07

### Added

- Add support to cast `Printer` to an array or json.

## 1.0.0 - 2020-06-26

- Initial release
