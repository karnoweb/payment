<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Http;

use Illuminate\Http\Request;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\Money;

class CallbackRequest extends Request
{
    public function transactionId(): ?string
    {
        // Zarinpal sends Authority
        return $this->input('Authority')
            ?? $this->input('authority')
            ?? $this->input('transaction_id');
    }

    public function status(): ?string
    {
        return $this->input('Status')
            ?? $this->input('ResCode')
            ?? $this->input('status');
    }

    public function isSuccessful(): bool
    {
        return $this->status() === 'OK';
    }

    public function toVerificationData(int|Money $amount): VerificationData
    {
        if (is_int($amount)) {
            $amount = Money::toman($amount);
        }

        return new VerificationData(
            transactionId: $this->transactionId(),
            amount: $amount,
            meta: $this->all(),
        );
    }
}
