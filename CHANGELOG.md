# Changelog

All notable changes to this project will be documented in this file.

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