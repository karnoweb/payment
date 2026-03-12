<?php

namespace Karnoweb\Payment\Drivers\Mellat;

use Karnoweb\Payment\Drivers\Abstracts\AbstractSoapGateway;
use Karnoweb\Payment\DTO\PurchaseData;
use Karnoweb\Payment\DTO\VerificationData;
use Karnoweb\Payment\DTO\PaymentResult;

class MellatDriver extends AbstractSoapGateway
{
    protected function wsdl(): string
    {
        return $this->config['wsdl']
            ?? 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';
    }

    public function purchase(PurchaseData $data): PaymentResult
    {
        $orderId = $data->orderId ?? time();

        $params = [
            'terminalId' => $this->config['terminal_id'],
            'userName' => $this->config['username'],
            'userPassword' => $this->config['password'],
            'orderId' => $orderId,
            'amount' => $data->amount->rial(),
            'localDate' => $this->nowDate(),
            'localTime' => $this->nowTime(),
            'additionalData' => $data->description ?? '',
            'callBackUrl' => $data->callbackUrl,
            'payerId' => 0,
        ];

        $response = $this->call('bpPayRequest', $params);

        $result = explode(',', $response->return);

        if ($result[0] == "0") {
            $refId = $result[1];

            return PaymentResult::success(
                transactionId: (string) $orderId,
                paymentUrl: "https://bpm.shaparak.ir/pgwchannel/startpay.mellat?RefId={$refId}",
                gateway: 'mellat'
            );
        }

        return PaymentResult::failed(
            message: "Mellat Error Code: {$result[0]}",
            gateway: 'mellat'
        );
    }

    public function verify(VerificationData $data): PaymentResult
    {
        $referenceId = $data->meta('SaleReferenceId');

        $params = [
            'terminalId' => $this->config['terminal_id'],
            'userName' => $this->config['username'],
            'userPassword' => $this->config['password'],
            'orderId' => $data->transactionId,
            'saleOrderId' => $data->transactionId,
            'saleReferenceId' => $referenceId,
        ];

        $verify = $this->call('bpVerifyRequest', $params);

        if ($verify->return == "0") {

            $this->call('bpSettleRequest', $params);

            return PaymentResult::success(
                transactionId: $data->transactionId,
                referenceId: $referenceId,
                gateway: 'mellat'
            );
        }

        return PaymentResult::failed(
            message: "Mellat Verify Error: {$verify->return}",
            transactionId: $data->transactionId,
            gateway: 'mellat'
        );
    }
}