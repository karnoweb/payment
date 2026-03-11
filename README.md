# Karnoweb Payment

A clean, extensible Laravel payment package for Iranian gateways.

## ✅ Features

- Zarinpal support
- IDPay support
- Unified API
- Money Value Object
- Event-driven
- Fake driver for testing
- Laravel 11 & 12 ready
- PHP 8.2+

---

## Installation

```bash
composer require karnoweb/payment
```

## Publish config:

```bash
php artisan vendor:publish --tag=payment-config
```

## Configuration

.env

```dotenv
PAYMENT_DRIVER=zarinpal

ZARINPAL_MERCHANT_ID=xxxx
ZARINPAL_SANDBOX=true

IDPAY_API_KEY=xxxx
IDPAY_SANDBOX=true
```

## Usage

✅ Create Payment

```php
use Karnoweb\Payment\Facades\Payment;

$result = Payment::driver()
    ->amount(10000)
    ->callback(route('callback'))
    ->description('Order #100')
    ->purchase();

if ($result->successful()) {
    return redirect($result->paymentUrl());
}
```

---
✅ Verify Payment

```php
$result = Payment::driver()
    ->transactionId($request->Authority)
    ->amount(10000)
    ->verify();

if ($result->successful()) {
    $referenceId = $result->referenceId();
}
```

---
✅ Testing

```php
Payment::fake();
```

Override result:

```php
FakeDriver::$purchaseResult = PaymentResult::success(...);
```

---
✅ Events

- PaymentRequested
- PaymentVerified
- PaymentFailed

---

## ✅ Creating a Custom Driver

You may register your own gateway driver:

### 1️⃣ Create your driver

```php
namespace App\Payments;

use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\DTO\PaymentResult;

class MellatDriver implements Gateway
{
    public function purchase(PurchaseData $data): PaymentResult
    {
        //
        
        return PaymentResult::success(
            transactionId: 'mellat_txn_123',
            paymentUrl: 'https://mellat.example/pay',
            gateway: 'mellat'
        );
    }

    public function verify(VerificationData $data): PaymentResult
    {
        //
        return PaymentResult::success(
            transactionId: $data->transactionId,
            referenceId: 'mellat_ref_123',
            gateway: 'mellat'
        );
    }
}
```
2️⃣ Register driver in AppServiceProvider:
```php
Payment::extend('mellat', function ($app) {
    return new MellatDriver();
});
```
3️⃣ Use it
```php
Payment::driver('mellat')->purchase();Payment::driver('mellat')->purchase();
```


## License

MIT