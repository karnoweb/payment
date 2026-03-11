<?php

namespace Karnoweb\Payment\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Karnoweb\Payment\PaymentServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            PaymentServiceProvider::class,
        ];
    }
}