<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Http;

use Illuminate\Http\Request;

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
            ?? $this->input('status');
    }

    public function isSuccessful(): bool
    {
        return $this->status() === 'OK';
    }
}
