<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Events;

class PaymentFailed
{
    public function __construct(
        public readonly string $gateway,
        public readonly string $message,
    ) {}
}
