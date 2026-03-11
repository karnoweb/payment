<?php

declare(strict_types=1);

namespace Karnoweb\Payment\DTO;

use Karnoweb\Payment\Money;

final class PurchaseData
{
    public function __construct(
        public readonly Money $amount,
        public readonly string $callbackUrl,
        public readonly ?string $description = null,
        public readonly ?string $mobile = null,
        public readonly ?string $email = null,
        public readonly ?string $orderId = null,
    ) {}
}
