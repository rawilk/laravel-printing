# Changelog

All notable changes to `laravel-printing` will be documented in this file.

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
