<?php

declare(strict_types=1);

namespace Karnoweb\Payment\DTO;

final class PaymentResult
{
    private function __construct(
        private bool $successful,
        private ?string $transactionId = null,
        private ?string $referenceId = null,
        private ?string $paymentUrl = null,
        private ?string $message = null,
        private ?string $gateway = null,
    ) {}

    public static function success(
        ?string $transactionId = null,
        ?string $referenceId = null,
        ?string $paymentUrl = null,
        ?string $gateway = null,
    ): self {
        return new self(true, $transactionId, $referenceId, $paymentUrl, null, $gateway);
    }

    public static function failed(
        string $message,
        ?string $transactionId = null,
        ?string $gateway = null,
    ): self {
        return new self(false, $transactionId, null, null, $message, $gateway);
    }

    public function successful(): bool
    {
        return $this->successful;
    }

    public function isFailed(): bool
    {
        return ! $this->successful;
    }

    public function transactionId(): ?string
    {
        return $this->transactionId;
    }

    public function referenceId(): ?string
    {
        return $this->referenceId;
    }

    public function paymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function message(): ?string
    {
        return $this->message;
    }

    public function gateway(): ?string
    {
        return $this->gateway;
    }
}
