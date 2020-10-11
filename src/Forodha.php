<?php


namespace Openpesa\SDK;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use phpseclib\Crypt\RSA;

class Forodha
{

    private $options;
    /**
     * @var null|Client
     */
    private $client;
    /**
     * @var null
     */
    private $rsa;

    const BASE_DOMAIN  = "https://openapi.m-pesa.com/sandbox/";
    
    const TRANSACT_TYPE = [
        'c2b' => [
            'name' => 'Consumer 2 Business',
            'url' => "https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/c2bPayment/singleStage/",
        ],
        'b2c' => [
            'name' => 'Business 2 Consumer',
            'url' =>"https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/b2cPayment/singleStage/",
        ],
    ];

    /**
     * Forodha constructor.
     * @param $options
     * @param null $client
     * @param null $rsa
     */
    public function __construct($options, $client = null, $rsa = null)
    {
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
     * @return mixed
     * @throws GuzzleException
     */
    public function get_session()
    {
        $response = $this->client->get($this->options['auth_url'],
            ['headers' => ['Authorization' => "Bearer {$this->encrypt_key($this->options['api_key'])}"]]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     *
     * @param $type
     * @param $data
     * @param $session
     * @return mixed
     * @throws GuzzleException
     */
    public function transact($type, $data, $session = null)
    {
        if (! $session)
            $session = $this->get_session()['output_SessionID'];

        $response = $this->client->post(self::TRANSACT_TYPE[$type]['url'], [
                'json' => $data,
                'headers' => ['Authorization' => "Bearer {$this->encrypt_key($session)}"]
            ]
        );
        return json_decode($response->getBody(), true);
    }
}
