<?php

namespace Karnoweb\Payment\Drivers\Abstracts;

use Karnoweb\Payment\Contracts\Gateway;
use SoapClient;
use SoapFault;
use Exception;

abstract class AbstractSoapGateway implements Gateway
{
    protected ?SoapClient $client = null;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    abstract protected function wsdl(): string;

    public function getClient(): SoapClient
    {
        if ($this->client === null) {
            $this->client = new SoapClient($this->wsdl(), [
                'encoding' => 'UTF-8',
                'exceptions' => true,
                'trace' => false,
            ]);
        }

        return $this->client;
    }

    protected function call(string $method, array $params)
    {
        try {
            return $this->getClient()->{$method}($params);
        } catch (SoapFault $e) {
            throw new Exception("SOAP Fault: " . $e->getMessage());
        }
    }

    protected function nowDate(): string
    {
        return date('Ymd');
    }

    protected function nowTime(): string
    {
        return date('His');
    }
}