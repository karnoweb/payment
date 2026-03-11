<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Events;

use Karnoweb\Payment\DTO\PurchaseData;

class PaymentRequested
{
    public function __construct(
        public readonly string $gateway,
        public readonly PurchaseData $data,
    ) {}
}
