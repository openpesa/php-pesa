<?php

namespace Openpesa\SDK;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use phpseclib\Crypt\RSA;

/**
 * @package Openpesa\SDK
 */
class Pesa
{

    /**
     * @var mixed
     * @access private
     */
    private $options;
    /**
     * @var null|Client
     * @access private
     */
    private $client;
    /**
     * @var null|RSA
     * @access private
     */
    private $rsa;
    /**
     * API URL
     * @var string
     * @access private
     */
    private $api_url = "";

    /**
     * BASE DOMAIN
     * @const string
     */
    const BASE_DOMAIN = "https://openapi.m-pesa.com";


    /**
     * AUTH URL
     * @const string
     */
    const AUTH_URL = self::BASE_DOMAIN . "/ipg/v2/vodacomTZN/getSession/";

    /**
     * TRANSACT TYPE
     *
     * @var array
     */
    const TRANSACT_TYPE = [
        'c2b' => [
            'name' => 'Consumer 2 Business',
            'url' => "c2bPayment/singleStage/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'b2c' => [
            'name' => 'Business 2 Consumer',
            'url' => "b2cPayment/singleStage/",
            'encryptSessionKey' => true,
            'rules' => []
        ],

        'b2b' => [
            'name' => 'Business 2 Business',
            'url' => "b2bPayment/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'rt' => [
            'name' => 'Reverse Transaction',
            'url' => "reversal/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'query' => [
            'name' => 'Query Transaction Status',
            'url' => "queryTransactionStatus/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'ddc' => [
            'name' => 'Direct Debits create',
            'url' => "directDebitCreation/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'ddp' => [
            'name' => 'Direct Debits payment',
            'url' => "directDebitPayment/",
            'encryptSessionKey' => false,
        ]

    ];

    /**
     * Pesa constructor.
     *
     *
     * @param $options array
     * @param null $client
     * @param null $rsa
     */
    public function __construct($options, $client = null, $rsa = null)
    {
        $options['client_options'] = $options['client_options'] ?? array();
        $this->options = $options;
        $this->client = ($client instanceof Client)
            ? $client
            : new Client(array_merge([
                'http_errors' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Origin' => '*'
                ]
            ], $options['client_options']));

        if (array_key_exists("env", $options)) {
            $this->api_url = ($options['env'] === "sandbox") ? self::BASE_DOMAIN . "/sandbox" : self::BASE_DOMAIN . "/openapi";
            $this->options['auth_url'] = ($options['env'] === "sandbox") ? self::BASE_DOMAIN . "/sandbox" : self::BASE_DOMAIN . "/openapi";
        } else {
            $this->api_url =  self::BASE_DOMAIN . "/openapi";
            $this->options['auth_url'] = self::BASE_DOMAIN . "/openapi";
        }
        $this->api_url .= "/ipg/v2/vodacomTZN/";
        $this->options['auth_url'] .= "/ipg/v2/vodacomTZN/getSession/";
        $this->rsa = ($rsa instanceof RSA) ? $rsa : new RSA();
    }

    /**
     * Encrypts public key
     *
     * @internal
     * @param $key
     * @return string
     */
    private function encrypt_key($key): string
    {
        $this->rsa->loadKey($this->options['public_key']);
        $this->rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        return base64_encode($this->rsa->encrypt($key));
    }

    /**
     * Get Session Key
     *
     * @api
     * @return mixed
     * @throws GuzzleException
     */
    public function get_session()
    {
        $response = $this->client->get(
            $this->options['auth_url'],
            ['headers' => ['Authorization' => "Bearer {$this->encrypt_key($this->options['api_key'])}"]]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     * The Query Transaction Status API call is used to query the status of the transaction that has been initiated.
     *
     * @api
     * @param $data mixed
     * @param $session null|mixed
     * @return mixed
     * @throws GuzzleException
     */
    public function query($data, $session = null)
    {
        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $response = $this->client->get($this->api_url . self::TRANSACT_TYPE['query']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$this->encrypt_key($session)}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * customer to business (C2B)
     *
     * The C2B API call is used as a standard customer-to-business transaction. Funds from the customer’s mobile money wallet will be deducted and be transferred to the mobile money wallet of the business. To authenticate and authorize this transaction, M-Pesa Payments Gateway will initiate a USSD Push message to the customer to gather and verify the mobile money PIN number. This number is not stored and is used only to authorize the transaction.
     *
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function c2b($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['c2b']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['c2b']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }


    /**
     * Business to Customer (B2C)
     *
     * The B2C API Call is used as a standard business-to-customer funds disbursement. Funds from the business account’s wallet will be deducted and paid to the mobile money wallet of the customer. Use cases for the B2C includes:
     *  -	Salary payments
     *  -	Funds transfers from business
     *  -	Charity pay-out
     *
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function b2c($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['b2c']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['b2c']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }


    /**
     * business to business (B2B)
     *
     * The B2B API Call is used for business-to-business transactions. Funds from the business’ mobile money wallet will be deducted and transferred to the mobile money wallet of the other business. Use cases for the B2C includes:
     *  -  Stock purchases
     *  -  Bill payment
     *  -  Ad-hoc payment
     *
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function b2b($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['b2b']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['rt']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Payment reversals
     *
     * The Reversal API is used to reverse a successful transaction. Using the Transaction ID of a previously successful transaction,  the OpenAPI will withdraw the funds from the recipient party’s mobile money wallet and revert the funds to the mobile money wallet of the initiating party of the original transaction.
     *
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function reverse($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['rt']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['rt']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }





    /**
     *
     * Direct Debit Create Mandate
     *
     *
     * Direct Debits are payments in M-Pesa that are initiated by the Payee alone without any Payer interaction, but permission must first be granted by the Payer. The granted permission from the Payer to Payee is commonly termed a ‘Mandate’, and M-Pesa must hold details of this Mandate.
     * The Direct Debit API set allows an organisation to get the initial consent of their customers to create the Mandate that allows the organisation to debit customer's account at an agreed frequency and amount for services rendered. After the initial consent, the debit of the account will not involve any customer interaction. The Direct Debit feature makes use of the following API calls:
     * •	Create a Direct Debit mandate
     * •	Pay a mandate
     * The customer is able to view and cancel the Direct Debit mandate from G2 menu accessible via USSD menu or the Smartphone Application.
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function debit_create($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['ddc']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['ddc']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Direct Debit Payment
     *
     * Direct Debits are payments in M-Pesa that are initiated by the Payee alone without any Payer interaction, but permission must first be granted by the Payer. The granted permission from the Payer to Payee is commonly termed a ‘Mandate’, and M-Pesa must hold details of this Mandate.
     * The Direct Debit API set allows an organisation to get the initial consent of their customers to create the Mandate that allows the organisation to debit customer's account at an agreed frequency and amount for services rendered. After the initial consent, the debit of the account will not involve any customer interaction. The Direct Debit feature makes use of the following API calls:
     * •	Create a Direct Debit mandate
     * •	Pay a mandate
     * The customer is able to view and cancel the Direct Debit mandate from G2 menu accessible via USSD menu or the Smartphone Application.
     *
     * @api
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function debit_payment($data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = ($this->api_url . self::TRANSACT_TYPE['ddp']['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post($this->api_url . self::TRANSACT_TYPE['ddp']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }
}
