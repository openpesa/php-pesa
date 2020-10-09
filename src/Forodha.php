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
    /**
     * @var false|string
     */
    protected $encrypted_api_key;

    /**
     * Forodha constructor.
     * @param $options
     * @param null $client
     * @param null $rsa
     */
    public function __construct($options, $client = null, $rsa = null)
    {
        $this->options = $options;
        $this->client = ($client instanceof Client) ? $client : new Client($options);
        $this->rsa = ($rsa instanceof RSA) ? $rsa : new RSA();

        // encrypt public key
        $this->encryptPublicKey();
    }

    /**
     * Encrypts public key
     * @return void
     */
    private function encryptPublicKey(): void
    {
        $this->rsa->loadKey($this->options['public_key']);
//        $this->rsa->setPublicKey($this->options['public_key']);
        $this->rsa->setEncryptionMode(RSA::ENCRYPTION_PKCS1);
        $this->encrypted_api_key = base64_encode($this->rsa->encrypt($this->options['api_key']));
    }

    /**
     * @return mixed
     * @throws GuzzleException
     */
    public function generate_session()
    {
        $response = $this->client->request('GET', $this->options['auth_url'],
            ['headers' => ['Authorization' => "Bearer {$this->encrypted_api_key}"]]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     *
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function transact($data)
    {

        $response = $this->client->post($this->options['auth_url'], [
                'json' => $data,
                'headers' => ['Authorization' => "Bearer {$this->encrypted_api_key}"]
            ]
        );
        return json_decode($response->getBody(), true);
    }
}
