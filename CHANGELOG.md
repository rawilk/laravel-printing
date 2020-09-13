# Changelog

All notable changes to `laravel-printing` will be documented in this file.

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
