<?php

declare(strict_types=1);

return [
    'default' => env('PAYMENT_DRIVER', 'zarinpal'),

    'currency' => env('PAYMENT_CURRENCY', 'toman'),

    'drivers' => [
        'zarinpal' => [
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
            'sandbox' => env('ZARINPAL_SANDBOX', false),
        ],
        'idpay' => [
            'api_key' => env('IDPAY_API_KEY'),
            'sandbox' => env('IDPAY_SANDBOX', false),
        ],
    ],
];
