<?php

declare(strict_types=1);

namespace Karnoweb\Payment;

use InvalidArgumentException;

final class Money
{
    private int $amount; // stored in rial
    private string $currency; // toman|rial

    private function __construct(int $amount, string $currency)
    {
        $this->currency = $currency;

        $this->amount = match ($currency) {
            'toman' => $amount * 10,
            'rial' => $amount,
            default => throw new InvalidArgumentException("Invalid currency [{$currency}]"),
        };
    }

    public static function toman(int $amount): self
    {
        return new self($amount, 'toman');
    }

    public static function rial(int $amount): self
    {
        return new self($amount, 'rial');
    }

    public static function fromRial(int $amount): self
    {
        return new self($amount, 'rial');
    }

    public function rial(): int
    {
        return $this->amount;
    }

    public function toman(): int
    {
        return intdiv($this->amount, 10);
    }

    public function equals(self $money): bool
    {
        return $this->amount === $money->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
