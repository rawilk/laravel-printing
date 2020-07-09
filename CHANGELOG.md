# Changelog

All notable changes to `laravel-printing` will be documented in this file.

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
