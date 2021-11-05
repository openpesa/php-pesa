<?php

namespace Openpesa\SDK;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

/**
 * @package Openpesa\SDK
 */
class Mpesa
{

    /**
     * @var array
     * @access private
     */
    private $options;
    /**
     * @var null|Client
     * @access private
     */
    private $client;

    private $config = include("Config.php")['mpesa'];
    private $apiUrl ;





    /**
     * BASE DOMAIN
     * @const string
     */

    const BASE_DOMAIN =  $config['live']['BASE_DOMAIN'];
    const SAND_BOX_DOMAIN = $config['sandbox']['BASE_DOMAIN'];

    /**
     * @access private
     * @var string $sessionToken
     */
    private $sessionToken;

    /**
     * TRANSACT TYPE
     *
     * @var array
     */
    const TRANSACT_TYPE = $config['mpesa']['transact_type'] ?? null;


    /**
     * Pesa constructor.
     *
     *
     * @param $options array
     * @param null $client
     * @param null $rsa
     */

    public function __construct(array $options, $client = null,)
    {
        if(array_key_exists("env", $options)){
            if($options['env'] == "sandbox"){
                $api_key = $this->config['sandbox']['api_key'];
                 $public_key = $this->config['sandbox']['public_key'];
                 $this->apiUrl = self::SAND_BOX_DOMAIN;
            }else{
                $api_key = $this->config['live']['api_key'];
                $public_key = $this->config['live']['public_key'];
                $this->apiUrl = self::BASE_DOMAIN;
            }
        }else{
            throw new  InvalidArgumentException("env is required");
        }

        if (empty($api_key)) throw new  InvalidArgumentException("api_key is required");
        if (empty($public_key)) throw new  InvalidArgumentException("public_key is required");

        $options['client_options'] = $options['client_options'] ?? [];
        $options['persistent_session'] = $options['persistent_session'] ?? false;

        $options['service_provider_code'] = $options['service_provider_code'] ?? null;
        $options['country'] = $options['country'] ?? null;
        $options['currency'] = $options['currency'] ?? null;

        $this->options = $options;
        $this->client = $this->makeClient($options, $client);
    }

    private function makeClient($options, $client = null): Client
    {
        $apiUrl = $this->apiUrl;

        $apiUrl .= "/ipg/v2/vodacomTZN/";


        return ($client instanceof Client)
            ? $client
            : new Client(array_merge([
                'http_errors' => false,
                'base_uri' => $apiUrl,
                'headers' => [
                    'Accept' => 'application/json',
                    'Origin' => '*'
                ]
            ], $options['client_options']));
    }
    /**
     * Encrypts public key
     *
     * @internal
     * @param $key
     * @return string
     */
    private function encryptKey($key): string
    {
        $publicKey = openssl_pkey_get_public("-----BEGIN PUBLIC KEY-----\n" . $this->options['public_key'] . "\n-----END PUBLIC KEY-----");
        if ($publicKey === false) {
            throw new Exception("Invalid public key");
        }

        openssl_public_encrypt($key, $encrypted, $publicKey, OPENSSL_PKCS1_OAEP_PADDING);
        return base64_encode($encrypted);
    }

    /**
     * Get Session Key
     *
     * @api
     * @return mixed
     * @throws GuzzleException
     */
    public function getSession()
    {
        $response = $this->client->get(
            'getSession/',
            ['headers' => ['Authorization' => "Bearer {$this->encryptKey($this->options['api_key'])}"]]
        );
        return json_decode($response->getBody(), true);
    }

    /**
     * Get Session Token
     * @return mixed
     * @throws GuzzleException
     * @throws Exception
     * @api
     */
    public function getSessionToken($session = null)
    {
        if ($session) return $session;

        if ($this->options['persistent_session'] == true && $this->sessionToken) {
            return $this->sessionToken;
        }

        $resSession =  $this->getSession();

        if ($resSession['output_ResponseCode'] == 'INS-0') {
            if ($this->options['persistent_session'] == true)
                $this->sessionToken = $resSession['output_SessionID'];
            return $resSession['output_SessionID'];
        } else {
            throw new Exception($resSession['output_ResponseDesc'] ?? "Error Processing Request", $resSession['output_ResponseCode'] ?? 0);
        }
    }

    /**
     * Make Request Data
     * @internal
     * @param $data mixed
     * @return mixed
     */
    private function makeRequestData($data)
    {

        $data['input_ServiceProviderCode'] = $data['input_ServiceProviderCode'] ?? $this->options['service_provider_code'];
        $data['input_Country'] = $data['input_Country'] ?? $this->options['country'];
        $data['input_Currency'] = $data['input_Currency'] ?? $this->options['currency'];

        return $data;
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
        $session = $this->getSessionToken($session);

        $transData = $this->makeRequestData($data);

        $response = $this->client->get(self::TRANSACT_TYPE['query']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$this->encryptKey($session)}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * customer to business (C2B)
     *
     * The C2B API call is used as a standard customer-to-business transaction. Funds from the customer’s mobile money wallet will be deducted and be transferred to the mobile money wallet of the business. To authenticate and authorize this transaction, M-Pesa Payments Gateway will initiate a USSD Push message to the customer to gather and verify the mobile money PIN number. This number is not stored and is used only to authorize the transaction.
     *
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function c2b($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $transData = $this->makeRequestData($data);

        $token = $this->encryptKey($sessionToken);

        $response = $this->client->post(self::TRANSACT_TYPE['c2b']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }


    /**
     * Business to Customer (B2C)
     *
     * The B2C API Call is used as a standard business-to-customer funds disbursement. Funds from the business account’s wallet will be deducted and paid to the mobile money wallet of the customer. Use cases for the B2C includes:
     *  -    Salary payments
     *  -    Funds transfers from business
     *  -    Charity pay-out
     *
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function b2c($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $token = $this->encryptKey($sessionToken);

        $transData = $this->makeRequestData($data);

        $response = $this->client->post(self::TRANSACT_TYPE['b2c']['url'], [
            'json' => $transData,
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
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function b2b($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $token = $this->encryptKey($sessionToken);

        $transData = $this->makeRequestData($data);

        $response = $this->client->post(self::TRANSACT_TYPE['rt']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Payment reversals
     *
     * The Reversal API is used to reverse a successful transaction. Using the Transaction ID of a previously successful transaction,  the OpenAPI will withdraw the funds from the recipient party’s mobile money wallet and revert the funds to the mobile money wallet of the initiating party of the original transaction.
     *
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function reverse($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $token = $this->encryptKey($sessionToken);

        $transData = $this->makeRequestData($data);

        $response = $this->client->post(self::TRANSACT_TYPE['rt']['url'], [
            'json' => $transData,
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
     * •    Create a Direct Debit mandate
     * •    Pay a mandate
     * The customer is able to view and cancel the Direct Debit mandate from G2 menu accessible via USSD menu or the Smartphone Application.
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function debit_create($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $token = $this->encryptKey($sessionToken);

        $transData = $this->makeRequestData($data);

        $response = $this->client->post(self::TRANSACT_TYPE['ddc']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$token}"]
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Direct Debit Payment
     *
     * Direct Debits are payments in M-Pesa that are initiated by the Payee alone without any Payer interaction, but permission must first be granted by the Payer. The granted permission from the Payer to Payee is commonly termed a ‘Mandate’, and M-Pesa must hold details of this Mandate.
     * The Direct Debit API set allows an organisation to get the initial consent of their customers to create the Mandate that allows the organisation to debit customer's account at an agreed frequency and amount for services rendered. After the initial consent, the debit of the account will not involve any customer interaction. The Direct Debit feature makes use of the following API calls:
     * •    Create a Direct Debit mandate
     * •    Pay a mandate
     * The customer is able to view and cancel the Direct Debit mandate from G2 menu accessible via USSD menu or the Smartphone Application.
     *
     * @param $data mixed
     * @param $session null|string
     * @return mixed
     * @throws GuzzleException
     * @api
     */
    public function debit_payment($data, $session = null)
    {

        $sessionToken = $this->getSessionToken($session);

        $transData = $this->makeRequestData($data);

        $response = $this->client->post(self::TRANSACT_TYPE['ddp']['url'], [
            'json' => $transData,
            'headers' => ['Authorization' => "Bearer {$sessionToken}"]
        ]);
        return json_decode($response->getBody(), true);
    }
}
