<?php

declare(strict_types=1);

namespace Karnoweb\Payment;

use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use Karnoweb\Payment\Contracts\Gateway;
use Closure;
class PaymentManager
{
    protected array $drivers = [];
    protected array $customCreators = [];

    public function __construct(
        protected Container $app
    ) {}

    public function driver(?string $name = null): PaymentBuilder
    {
        $name ??= $this->getDefaultDriver();

        $gateway = $this->resolve($name);

        return new PaymentBuilder($gateway, $name);
    }

    public function extend(string $name, Closure $resolver): void
    {
        $this->customCreators[$name] = $resolver;
    }

    protected function resolve(string $name): Gateway
    {
        if (isset($this->drivers[$name])) {
            return $this->drivers[$name];
        }

        // ✅ 1. Custom drivers (highest priority)
        if (isset($this->customCreators[$name])) {
            return $this->drivers[$name] =
                $this->customCreators[$name]($this->app);
        }

        // ✅ 2. Built-in registry
        if (\Karnoweb\Payment\Support\DriverRegistry::has($name)) {

            $class = \Karnoweb\Payment\Support\DriverRegistry::get($name);

            $config = $this->app['config']["payment.drivers.$name"] ?? [];

            return $this->drivers[$name] =
                $this->app->make($class, ['config' => $config]);
        }

        throw new InvalidArgumentException(
            "Payment driver [$name] is not supported."
        );
    }

    protected function getDefaultDriver(): string
    {
        return $this->app['config']['payment.default'];
    }

    public function fake(): void
    {
        $this->drivers['fake'] = new Drivers\Fake\FakeDriver;
        $this->app['config']->set('payment.default', 'fake');
    }
}
