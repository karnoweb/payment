<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Karnoweb\Payment\PaymentBuilder driver(string $name)
 */
class Payment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
