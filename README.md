# Karnoweb Payment

A clean, extensible Laravel payment framework for Iranian gateways.

![Tests](https://github.com/karnoweb/payment/actions/workflows/tests.yml/badge.svg)
![License](https://img.shields.io/github/license/karnoweb/payment)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php)
![Laravel](https://img.shields.io/badge/Laravel-11%20%7C%2012-FF2D20?logo=laravel)

---

## ✅ Features

- Unified payment API
- Driver auto-registration
- Custom driver support
- Money Value Object
- Event-driven architecture
- Fake driver for testing
- SOAP & REST support
- Laravel 11 & 12 ready

---

## 📦 Installation

```bash
composer require karnoweb/payment
```

```bash
php artisan vendor:publish --tag=payment-config
```

---

## 📚 Documentation

- [Installation](docs/installation.md)
- [Configuration](docs/configuration.md)
- [Usage](docs/usage.md)
- [Verification](docs/verification.md)
- [Testing](docs/testing.md)
- [Events](docs/events.md)
- [Custom Driver](docs/custom-driver.md)
- [Gateways](docs/gateways/)
- [Roadmap](docs/roadmap.md)
- [Contributing](docs/contributing.md)

---

## License

MIT