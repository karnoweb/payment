<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Drivers\IDPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;

class IDPayDriver implements Gateway
{
    protected Client $client;
    protected string $apiKey;
    protected bool $sandbox;

    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'];
        $this->sandbox = $config['sandbox'] ?? false;

        $this->client = new Client([
            'base_uri' => 'https://api.idpay.ir/v1.1/',
            'timeout' => 10,
        ]);
    }

    public function purchase(PurchaseData $data): PaymentResult
    {
        try {
            $response = $this->client->post('payment', [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'X-SANDBOX' => $this->sandbox ? '1' : '0',
                ],
                'json' => [
                    'amount' => $data->amount->rial(),
                    'callback' => $data->callbackUrl,
                    'description' => $data->description ?? 'Payment',
                    'order_id' => $data->orderId ?? uniqid(),
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return PaymentResult::success(
                transactionId: $body['id'] ?? null,
                paymentUrl: $body['link'] ?? null,
                gateway: 'idpay',
            );
        } catch (GuzzleException $e) {
            return PaymentResult::failed(
                message: $e->getMessage(),
                gateway: 'idpay',
            );
        }
    }

    public function verify(VerificationData $data): PaymentResult
    {
        try {
            $response = $this->client->post('payment/verify', [
                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'X-SANDBOX' => $this->sandbox ? '1' : '0',
                ],
                'json' => [
                    'id' => $data->transactionId,
                    'order_id' => $data->orderId,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return PaymentResult::success(
                transactionId: $data->transactionId,
                referenceId: $body['payment']['track_id'] ?? null,
                gateway: 'idpay',
            );
        } catch (GuzzleException $e) {
            return PaymentResult::failed(
                message: $e->getMessage(),
                transactionId: $data->transactionId,
                gateway: 'idpay',
            );
        }
    }
}
