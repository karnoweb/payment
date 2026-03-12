# Testing

```php
Payment::fake();
```

Override result:

```php
FakeDriver::$purchaseResult = PaymentResult::success(...);
```