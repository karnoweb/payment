<?php

declare(strict_types=1);

namespace Karnoweb\Payment\DTO;

use Karnoweb\Payment\Money;

final class VerificationData
{
    public function __construct(
        public readonly string $transactionId,
        public readonly Money $amount,
        public readonly ?string $orderId = null,
        public readonly array $meta = []
    ) {
    }

    public function meta(string $key): mixed
    {
        return $this->meta[$key] ?? null;
    }
}
