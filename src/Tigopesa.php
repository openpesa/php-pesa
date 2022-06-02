<?php

namespace Openpesa\SDK;

use Exception;
use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * @package Openpesa\SDK
 */
class Tigopesa
{

    public  const ACCESS_TOKEN_ENDPOINT = "/v1/oauth/generate/accesstoken?grant_type=client_credentials";

    public const PAYMENT_AUTHORIZATION_ENDPOINT = "/v1/tigo/payment-auth/authorize";

    private $optionsKeys = [
'clientId',
'secret',
'baseUrl',
'pin',
'accountNumber',
'accountId',
'appUrl',
'redirectUrl',
'callbackUrl',
'currencyCode',
'lang',
    ];


    private $baseUrl;
    private $options;
    private $client;

    public function __construct($options , $client)
    {
        foreach ($this->optionsKeys as $key) {
            if (!array_key_exists($key, $options)) {
                throw new InvalidArgumentException("Missing option: $key");
            }
        }
        $this->options = $options;
        $this->client = $client ?: new Client();

    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }


    // Get current options
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Change all Options
     * @param array $options
     *
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function updateOptions($options)
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * Generate random string with certain length
     *
     * @param string $prefix
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($prefix = 'TIGOPESA', $length = 15){
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $prefix . $randomString;
    }



    /**
     * Create Payment JSON
     * @param $amount
     * @param $reference_id
     * @param $customerFirstName
     * @param $customerLastName
     * @param $customerEmail
     * @return string
     *
     */

    public function createPaymentAuthJson(
        $amount,
        $referenceId,
        $customerFirstName,
        $customerLastName,
        $customerEmail
    ) {


        $paymentJson = '{
  "MasterMerchant": {
    "account": "' . $this->options['account_number'] . '",
    "pin": "' . $this->options['pin'] . '",
    "id": "' . $this->options['account_id'] . '"
  },
  "Merchant": {
    "reference": "",
    "fee": "0.00",
    "currencyCode": ""
  },
  "Subscriber": {
    "account": "",
    "countryCode": "255",
    "country": "TZA",
    "firstName": "' . $customerFirstName . '",
    "lastName": "' . $customerLastName . '",
    "emailId": "' . $customerEmail . '"
  },
  "redirectUri":" ' . $this->options['redirect_url'] . '",
  "callbackUri": "' . $this->options['callback_url'] . '",
  "language": "' . $this->options['lang'] . '",
  "terminalId": "",
  "originPayment": {
    "amount": "' . $amount . '",
    "currencyCode": "' . $this->options['currency_code'] . '",
    "tax": "0.00",
    "fee": "0.00"
  },
  "exchangeRate": "1",
  "LocalPayment": {
    "amount": "' . $amount . '",
    "currencyCode": "' . $this->options['currency_code'] . '"
  },
  "transactionRefId": "' . $referenceId . '"
}';
        return $paymentJson;
    }


    /**
     * Make Payment Request Using CURL
     *
     * @param $issuedToken
     * @param $amount
     * @param $referenceId
     * @param $customerFirstName
     * @param $customerLastName
     * @param $customerEmail
     *
     * @return mixed
     *
     */

    public function makePaymentRequest($issuedToken, $amount, $referenceId, $customerFirstName, $customerLastName, $customerEmail)
    {

        $paymentAuthUrl = $this->baseUrl . '/v1/tigo/payment-auth/authorize';

        $response = $this->client->request('POST', $paymentAuthUrl, [
            'headers' => [
                'accesstoken' => $issuedToken,
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
            ],
            'body' => $this->createPaymentAuthJson($amount, $referenceId, $customerFirstName, $customerLastName, $customerEmail)
        ]);

        return $response->getBody();
    }

    public function c2b($data)
    {

        $paymentAuthUrl = $this->baseUrl . '/v1/tigo/payment-auth/authorize';

        $response = $this->client->request('POST', $paymentAuthUrl, [
            'headers' => [
                'accesstoken' => $data['issuedToken'],
                'cache-control' => 'no-cache',
                'content-type' => 'application/json',
            ],
            'body' => $this->createPaymentAuthJson($data['amount'], $data['referenceId'], $data['customerFirstName'], $data['customerLastName'], $data['customerEmail'])
        ]);

        return $response->getBody();
    }

    public function getAccessToken()
    {
        $accessTokenUrl = $this->baseUrl . '/v1/oauth/generate/accesstoken?grant_type=client_credentials';

        $data = [
            'client_id' => $this->options['client_id'],
            'client_secret' => $this->options['secret']
        ];

        $response = $this->client->request('POST', $accessTokenUrl, [
            'form_params' => $data
        ]);
        return $response->getBody();
    }
}
