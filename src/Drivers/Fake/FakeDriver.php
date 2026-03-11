<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Drivers\Fake;

use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;

class FakeDriver implements Gateway
{
    public static ?PaymentResult $purchaseResult = null;
    public static ?PaymentResult $verifyResult = null;

    public static array $purchases = [];
    public static array $verifications = [];

    public function purchase(PurchaseData $data): PaymentResult
    {
        self::$purchases[] = $data;

        return self::$purchaseResult
            ?? PaymentResult::success(
                transactionId: 'fake_txn_123',
                paymentUrl: 'https://fake-gateway.test/pay',
                gateway: 'fake'
            );
    }

    public function verify(VerificationData $data): PaymentResult
    {
        self::$verifications[] = $data;

        return self::$verifyResult
            ?? PaymentResult::success(
                transactionId: $data->transactionId,
                referenceId: 'fake_ref_123',
                gateway: 'fake'
            );
    }

    public static function reset(): void
    {
        self::$purchaseResult = null;
        self::$verifyResult = null;
        self::$purchases = [];
        self::$verifications = [];
    }
}
