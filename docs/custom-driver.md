# Creating a Custom Driver

## 1️⃣ Create Driver

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

## 2️⃣ Register Driver

```php
Payment::extend('mellat', function ($app) {
    return new MellatDriver();
});
```

## 3️⃣ Use Driver

```php
Payment::driver('mellat')->purchase();
```