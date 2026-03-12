# Creating a Payment

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