# Changelog

All notable changes to this project will be documented in this file.

## [13.0.0] - 2026-07-08

### Added

- Laravel 13 support (dedicated release line).

### Changed

- Minimum PHP version raised to 8.3.
- `illuminate/support` and `illuminate/http` now require `^13.0`.

### Notes

- For Laravel 11–12, continue using the `^1.0` release line.

## [1.3.0] - 2026-06-08

### Changed
- Rename `Money::rial()` to `Money::toRial()` and `Money::toman()` to `Money::toToman()`
- Remove unused `Money::fromRial()` factory
- Rename `PaymentResult::failed()` to `PaymentResult::isFailed()`

## [1.0.0] - 2026-03-11

### Added
- Zarinpal driver
- IDPay driver
- Fake driver for testing
- Custom driver support
- Event system
- CallbackRequest abstraction
- Money Value Object
- Laravel 11 & 12 support
