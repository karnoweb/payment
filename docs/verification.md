# Verifying a Payment

```php
$result = Payment::driver()
    ->transactionId($request->Authority)
    ->amount(10000)
    ->verify();

if ($result->successful()) {
    $referenceId = $result->referenceId();
}
```