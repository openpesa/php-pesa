<?php

namespace Openpesa\SDK;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use phpseclib\Crypt\RSA;

class Forodha
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
     * BASE DOMAIN
     * @const string
     */
    const BASE_DOMAIN = "https://openapi.m-pesa.com/sandbox/";

    /**
     * AUTH URL
     * @const string
     */
    const AUTH_URL = self::BASE_DOMAIN . "ipg/v2/vodacomTZN/getSession/";

    /**
     * TRANSACT TYPE
     * @var array
     */
    const TRANSACT_TYPE = [
        'c2b' => [
            'name' => 'Consumer 2 Business',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/c2bPayment/singleStage/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'b2c' => [
            'name' => 'Business 2 Consumer',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/b2cPayment/singleStage/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'rt' => [
            'name' => 'Reverse Transaction',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/reversal/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'query' => [
            'name' => 'Query Transaction Status',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/queryTransactionStatus/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'ddc' => [
            'name' => 'Direct Debits create',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/directDebitCreation/",
            'encryptSessionKey' => true,
            'rules' => []
        ],
        'ddp' => [
            'name' => 'Direct Debits payment',
            'url' => self::BASE_DOMAIN . "ipg/v2/vodacomTZN/directDebitPayment/",
            'encryptSessionKey' => false,
        ]

    ];

    /**
     * Forodha constructor.
     * @param $options array
     * @param null $client
     * @param null $rsa
     */
    public function __construct($options, $client = null, $rsa = null)
    {

        $options['auth_url'] = $options['auth_url'] ?? self::AUTH_URL;
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

        $this->rsa = ($rsa instanceof RSA) ? $rsa : new RSA();
    }

    /**
     * Encrypts public key
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
     * Query the status of the transaction that has been initiated.
     *
     * @param $data mixed
     * @param $session null|mixed
     * @return mixed
     * @throws GuzzleException
     */
    public function query($data, $session = null)
    {
        if (!$session)
            $session = $this->get_session()['output_SessionID'];

        $response = $this->client->get(self::TRANSACT_TYPE['query']['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$this->encrypt_key($session)}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Perform a transaction
     *
     * @param $type string
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     */
    public function transact(string $type, $data, $session = null)
    {

        $session = ($session) ?? $this->get_session()['output_SessionID'];

        $token = (self::TRANSACT_TYPE[$type]['encryptSessionKey']) ? $this->encrypt_key($session) : $session;

        $response = $this->client->post(self::TRANSACT_TYPE[$type]['url'], [
            'json' => $data,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }
}
