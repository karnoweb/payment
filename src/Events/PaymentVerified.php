<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Events;

use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\VerificationData;

class PaymentVerified
{
    public function __construct(
        public readonly string $gateway,
        public readonly VerificationData $data,
        public readonly PaymentResult $result,
    ) {}
}
