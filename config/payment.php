<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment driver that will be used
    | when calling Payment::driver() without specifying a driver.
    |
    */

    'default' => env('PAYMENT_DRIVER', 'zarinpal'),


    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | Supported values: "toman", "rial"
    |
    */

    'currency' => env('PAYMENT_CURRENCY', 'toman'),


    /*
    |--------------------------------------------------------------------------
    | Drivers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the credentials for each payment gateway.
    | You can also add your own custom drivers.
    |
    */

    'drivers' => [

        /*
        |--------------------------------------------------------------------------
        | Zarinpal
        |--------------------------------------------------------------------------
        */
        'zarinpal' => [
            'merchant_id' => env('ZARINPAL_MERCHANT_ID'),
            'sandbox' => env('ZARINPAL_SANDBOX', false),
        ],

        /*
        |--------------------------------------------------------------------------
        | IDPay
        |--------------------------------------------------------------------------
        */
        'idpay' => [
            'api_key' => env('IDPAY_API_KEY'),
            'sandbox' => env('IDPAY_SANDBOX', false),
        ],

        /*
        |--------------------------------------------------------------------------
        | Mellat (Behpardakht)
        |--------------------------------------------------------------------------
        */
        'mellat' => [
            'terminal_id' => env('MELLAT_TERMINAL_ID'),
            'username'    => env('MELLAT_USERNAME'),
            'password'    => env('MELLAT_PASSWORD'),
            'wsdl'        => env(
                'MELLAT_WSDL',
                'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl'
            ),
        ],

        /*
        |--------------------------------------------------------------------------
        | Parsian
        |--------------------------------------------------------------------------
        */
        'parsian' => [
            'pin'  => env('PARSIAN_PIN'),
            'wsdl' => env(
                'PARSIAN_WSDL',
                'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl'
            ),
        ],

        /*
        |--------------------------------------------------------------------------
        | Saman (SEP)
        |--------------------------------------------------------------------------
        */
        'saman' => [
            'terminal_id' => env('SAMAN_TERMINAL_ID'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Fake (Testing)
        |--------------------------------------------------------------------------
        */
        'fake' => [
            // no configuration required
        ],

    ],

];