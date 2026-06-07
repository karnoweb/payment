<?php

declare(strict_types=1);

namespace Karnoweb\Payment;

use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\Events\PaymentFailed;
use Karnoweb\Payment\Events\PaymentRequested;
use Karnoweb\Payment\Events\PaymentVerified;

class PaymentBuilder
{
    protected ?Money $amount = null;
    protected ?string $callbackUrl = null;
    protected ?string $description = null;
    protected ?string $mobile = null;
    protected ?string $email = null;
    protected ?string $orderId = null;
    protected ?string $transactionId = null;

    public function __construct(
        protected Gateway $gateway,
        protected string $driverName,
    ) {}

    public function amount(Money|int $amount): self
    {
        if (is_int($amount)) {
            $currency = config('payment.currency', 'toman');
            $amount = $currency === 'rial'
                ? Money::rial($amount)
                : Money::toman($amount);
        }

        $this->amount = $amount;

        return $this;
    }

    public function callback(string $url): self
    {
        $this->callbackUrl = $url;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function mobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function orderId(string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function transactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function purchase(): PaymentResult
    {
        $this->ensurePurchaseIsValid();

        $data = new PurchaseData(
            amount: $this->amount,
            callbackUrl: $this->callbackUrl,
            description: $this->description,
            mobile: $this->mobile,
            email: $this->email,
            orderId: $this->orderId,
        );

        Event::dispatch(new PaymentRequested($this->driverName, $data));

        $result = $this->gateway->purchase($data);

        if ($result->isFailed()) {
            Event::dispatch(new PaymentFailed(
                $this->driverName,
                $result->message() ?? 'Payment failed'
            ));
        }

        return $result;
    }

    public function verify(): PaymentResult
    {
        $this->ensureVerificationIsValid();

        $data = new VerificationData(
            transactionId: $this->transactionId,
            amount: $this->amount,
            orderId: $this->orderId,
        );

        $result = $this->gateway->verify($data);

        if ($result->successful()) {
            Event::dispatch(new PaymentVerified(
                $this->driverName,
                $data,
                $result
            ));
        } else {
            Event::dispatch(new PaymentFailed(
                $this->driverName,
                $result->message() ?? 'Verification failed'
            ));
        }

        return $result;
    }

    protected function ensurePurchaseIsValid(): void
    {
        if ( ! $this->amount) {
            throw new InvalidArgumentException('Payment amount is required.');
        }

        if ( ! $this->callbackUrl) {
            throw new InvalidArgumentException('Callback URL is required.');
        }
    }

    protected function ensureVerificationIsValid(): void
    {
        if ( ! $this->amount) {
            throw new InvalidArgumentException('Verification amount is required.');
        }

        if ( ! $this->transactionId) {
            throw new InvalidArgumentException('Transaction ID is required.');
        }
    }
}
