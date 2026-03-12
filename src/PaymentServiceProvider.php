<?php

declare(strict_types=1);

namespace Karnoweb\Payment;

use Illuminate\Support\ServiceProvider;
use Karnoweb\Payment\Support\DriverRegistry;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/payment.php',
            'payment'
        );

        $this->app->singleton(PaymentManager::class, function ($app) {
            return new PaymentManager($app);
        });

        $this->app->singleton('payment', function ($app) {
            return $app->make(PaymentManager::class);
        });

        $this->registerBuiltInDrivers();
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/payment.php' => config_path('payment.php'),
        ], 'payment-config');
    }

    protected function registerBuiltInDrivers(): void
    {
        DriverRegistry::register('zarinpal',
            \Karnoweb\Payment\Drivers\Zarinpal\ZarinpalDriver::class);

        DriverRegistry::register('idpay',
            \Karnoweb\Payment\Drivers\IDPay\IDPayDriver::class);

        DriverRegistry::register('mellat',
            \Karnoweb\Payment\Drivers\Mellat\MellatDriver::class);

        DriverRegistry::register('parsian',
            \Karnoweb\Payment\Drivers\Parsian\ParsianDriver::class);

        DriverRegistry::register('saman',
            \Karnoweb\Payment\Drivers\Saman\SamanDriver::class);

        DriverRegistry::register('fake',
            \Karnoweb\Payment\Drivers\Fake\FakeDriver::class);
    }
}
