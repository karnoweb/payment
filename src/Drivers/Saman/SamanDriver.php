<?php

namespace Karnoweb\Payment\Drivers\Saman;

use GuzzleHttp\Client;
use Karnoweb\Payment\Contracts\Gateway;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\DTO\PaymentResult;

class SamanDriver implements Gateway
{
    protected Client $client;
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    public function purchase(PurchaseData $data): PaymentResult
    {
        $response = $this->client->post(
            'https://sep.shaparak.ir/OnlinePG/OnlinePG',
            [
                'form_params' => [
                    'action' => 'token',
                    'TerminalId' => $this->config['terminal_id'],
                    'Amount' => $data->amount->toRial(),
                    'ResNum' => $data->orderId ?? time(),
                    'RedirectUrl' => $data->callbackUrl,
                ]
            ]
        );

        $token = trim($response->getBody()->getContents());

        return PaymentResult::success(
            transactionId: (string)($data->orderId ?? time()),
            paymentUrl: "https://sep.shaparak.ir/payment.aspx?token={$token}",
            gateway: 'saman'
        );
    }

    public function verify(VerificationData $data): PaymentResult
    {
        $referenceId = $data->meta('RefNum');

        return PaymentResult::success(
            transactionId: $data->transactionId,
            referenceId: $referenceId,
            gateway: 'saman'
        );
    }
}