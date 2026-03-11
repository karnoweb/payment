<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Contracts;

use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;

interface Gateway
{
    public function purchase(PurchaseData $data): PaymentResult;

    public function verify(VerificationData $data): PaymentResult;
}
