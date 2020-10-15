<?php

namespace Openpesa\SDK\Tests;

class Fixture
{
    public static $username = 'sandbox';
    public static $apiKey = '';
    public static $publicKey = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ==';
    public static $authUrl = 'https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/getSession/';
    public static $transactionType = [
        'c2b' => [
            'name' => 'Consumer 2 Business',
            'url' => 'https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/c2bPayment/singleStage/',
        ],
        'b2c' => [
            'name' => 'Business 2 Consumer',
            'url' => "https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/b2cPayment/singleStage/",
        ],
    ];
    public static $data_c2b = [
        'input_Amount' => 2030,
        'input_Country' => 'TZN',
        'input_Currency' => 'TZS',
        'input_CustomerMSISDN' => '000000000001',
        'input_ServiceProviderCode' => '000000',
        'input_ThirdPartyConversationID' => 'rerekf',
        'input_TransactionReference' => 'odfdferre',
        'input_PurchasedItemsDesc' => 'Test Two Item'
    ];
    public static $data_b2c = [
        'input_ReversalAmount' => 12.12,
        'input_TransactionID' => 'odfdferre',
        'input_Country' => 'TZN',
        'input_ServiceProviderCode' => '000000',
        'input_ThirdPartyConversationID' => 'asv02e5958774f7ab228d83d0d689761',
    ];

    public static $data_reversal = [
        'input_Amount' => 5030,
        'input_Country' => 'TZN',
        'input_Currency' => 'TZS',
        'input_CustomerMSISDN' => '000000000001',
        'input_ServiceProviderCode' => '000000',
        'input_ThirdPartyConversationID' => 'asv02e5958774f7ab228d83d0d689761',
        'input_TransactionReference' => 'odfdferre',
        'input_PurchasedItemsDesc' => 'Salary payment'
    ];

    public static $data_query = [
        'input_QueryReference' => '000000000000000000001',
        'input_ServiceProviderCode' => '000000',
        'input_ThirdPartyConversationID' => 'asv02e5958774f7ba228d83d0d689761',
        'input_Country' => 'TZN'

    ];


    public static $data_ddc = [
        "input_AgreedTC" => "1",
        "input_Country" => "TNZ",
        "input_CustomerMSISDN" => "000000000001",
        "input_EndRangeOfDays" => "22",
        "input_ExpiryDate" => "20201126",
        "input_FirstPaymentDate" => "20200324",
        "input_Frequency" => "06", // Half Yearly
        "input_ServiceProviderCode" => "000000",
        "input_StartRangeOfDays" => "01",
        "input_ThirdPartyConversationID" => "AAA6d1f9391a0052de0b5334a912jbsj1j2kk",
        "input_ThirdPartyReference" => "3333"
    ];


    public static $data_ddp = [
        "input_Amount" => "10",
        "input_Country" => "TNZ",
        "input_Currency" => "GHS",
        "input_CustomerMSISDN" => "000000000001",
        "input_ServiceProviderCode" => "000000",
        "input_ThirdPartyConversationID" => "AAA6d1f939c1005v2de053v4912jbasdj1j2kk",
        "input_ThirdPartyReference" => "5db410b459bd433ca8e5"
    ];

    public static function data()
    {
        return [
            'input_Amount' => rand(1000, 2000),
            'input_Country' => 'TZN',
            'input_Currency' => 'TZS',
            'input_CustomerMSISDN' => '255766303775',
            'input_ServiceProviderCode' => '000000',
            'input_ThirdPartyConversationID' => 'rerekf',
            'input_TransactionReference' => rand(),
            'input_PurchasedItemsDesc' => 'Test Two Item'
        ];
    }
}
