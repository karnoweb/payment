<?php

namespace Karnoweb\Payment\Drivers\Contracts;

use SoapClient;

interface SoapGateway
{
    public function getClient(): SoapClient;
}