<?php

namespace Karnoweb\Payment\Drivers\Parsian;

use Karnoweb\Payment\Drivers\Abstracts\AbstractSoapGateway;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\DTO\PaymentResult;

class ParsianDriver extends AbstractSoapGateway
{
    protected function wsdl(): string
    {
        return $this->config['wsdl']
            ?? 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl';
    }

    public function purchase(PurchaseData $data): PaymentResult
    {
        $orderId = $data->orderId ?? time();

        $params = [
            'requestData' => [
                'LoginAccount' => $this->config['pin'],
                'Amount' => $data->amount->toRial(),
                'OrderId' => $orderId,
                'CallBackUrl' => $data->callbackUrl,
            ]
        ];

        $response = $this->call('SalePaymentRequest', $params);

        if ($response->SalePaymentRequestResult->Status == 0) {

            $token = $response->SalePaymentRequestResult->Token;

            return PaymentResult::success(
                transactionId: (string)$orderId,
                paymentUrl: "https://pec.shaparak.ir/NewIPG/?Token={$token}",
                gateway: 'parsian'
            );
        }

        return PaymentResult::failed(
            message: "Parsian Error: " .
            $response->SalePaymentRequestResult->Status,
            gateway: 'parsian'
        );
    }

    public function verify(VerificationData $data): PaymentResult
    {
        $token = $data->meta('Token');

        $params = [
            'requestData' => [
                'LoginAccount' => $this->config['pin'],
                'Token' => $token,
            ]
        ];

        $response = $this->call('SalePaymentVerification', $params);

        if ($response->SalePaymentVerificationResult->Status == 0) {

            return PaymentResult::success(
                transactionId: $data->transactionId,
                referenceId:
                $response->SalePaymentVerificationResult->RRN,
                gateway: 'parsian'
            );
        }

        return PaymentResult::failed(
            message: "Parsian Verify Error",
            transactionId: $data->transactionId,
            gateway: 'parsian'
        );
    }
}