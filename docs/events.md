# Events

Available events:

- PaymentRequested
- PaymentVerified
- PaymentFailed

Example:

```php
Event::listen(PaymentVerified::class, function ($event) {
    //
});
```