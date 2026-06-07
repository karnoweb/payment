<?php

declare(strict_types=1);

namespace Karnoweb\Payment\Drivers\Zarinpal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PaymentResult;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;

class ZarinpalDriver implements Gateway
{
    protected Client $client;
    protected string $merchantId;
    protected bool $sandbox;

    public function __construct(array $config)
    {
        $this->merchantId = $config['merchant_id'];
        $this->sandbox = $config['sandbox'] ?? false;

        $this->client = new Client([
            'base_uri' => $this->sandbox
                ? 'https://sandbox.zarinpal.com'
                : 'https://api.zarinpal.com',
            'timeout' => 10,
        ]);
    }

    public function purchase(PurchaseData $data): PaymentResult
    {
        try {
            $response = $this->client->post('/pg/v4/payment/request.json', [
                'json' => [
                    'merchant_id' => $this->merchantId,
                    'amount' => $data->amount->toRial(),
                    'callback_url' => $data->callbackUrl,
                    'description' => $data->description ?? 'Payment',
                    'metadata' => array_filter([
                        'mobile' => $data->mobile,
                        'email' => $data->email,
                    ]),
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            if (($body['data']['code'] ?? null) === 100) {
                $authority = $body['data']['authority'];

                $paymentUrl = ($this->sandbox
                        ? 'https://sandbox.zarinpal.com'
                        : 'https://www.zarinpal.com')
                    . '/pg/StartPay/' . $authority;

                return PaymentResult::success(
                    transactionId: $authority,
                    paymentUrl: $paymentUrl,
                    gateway: 'zarinpal',
                );
            }

            return PaymentResult::failed(
                message: $body['errors']['message'] ?? 'Payment request failed.',
                gateway: 'zarinpal',
            );
        } catch (GuzzleException $e) {
            return PaymentResult::failed(
                message: $e->getMessage(),
                gateway: 'zarinpal',
            );
        }
    }

    public function verify(VerificationData $data): PaymentResult
    {
        try {
            $response = $this->client->post('/pg/v4/payment/verify.json', [
                'json' => [
                    'merchant_id' => $this->merchantId,
                    'amount' => $data->amount->toRial(),
                    'authority' => $data->transactionId,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            $code = $body['data']['code'] ?? null;

            if ($code === 100 || $code === 101) {
                return PaymentResult::success(
                    transactionId: $data->transactionId,
                    referenceId: $body['data']['ref_id'] ?? null,
                    gateway: 'zarinpal',
                );
            }

            return PaymentResult::failed(
                message: $body['errors']['message'] ?? 'Verification failed.',
                transactionId: $data->transactionId,
                gateway: 'zarinpal',
            );
        } catch (GuzzleException $e) {
            return PaymentResult::failed(
                message: $e->getMessage(),
                transactionId: $data->transactionId,
                gateway: 'zarinpal',
            );
        }
    }
}
