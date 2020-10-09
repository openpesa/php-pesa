<?php

namespace Openpesa\SDK;

require 'vendor/autoload.php';

use Exception;
use GuzzleHttp\Client;
use phpseclib\Crypt\RSA;

class Pesa
{
    protected $client;
    private $APIKEY = "";
    const BASE_DOMAIN         = "https://openapi.m-pesa.com";
    const BASE_SANDBOX_DOMAIN = self::BASE_DOMAIN . "/sandbox/";

    private $publicKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ==";


    public function __construct()
    {
        $this->client = new Client([            
            'timeout'  => 300,
            'headers' => [
                'Accept' => 'application/json',
                'Origin' => '*'
            ]
        ]);
    }

    public function GenerateSessionKey()
    {
        $response = $this->client->request(
            'GET',
            'https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/getSession/',
            ['headers' => ['Authorization' => "Bearer {$this->create_bearer_token($this->APIKEY)}"]]
        );
        $body = json_decode($response->getBody());
        // print_r($body);
        return $body->output_SessionID;
    }

    function create_bearer_token($apiKEY)
    {
        // Need to do these lines to create a 'valid' formatted RSA key for the openssl library
        $rsa = new RSA();
        $rsa->loadKey($this->publicKey);
        $rsa->setPublicKey($this->publicKey);

        $publickey = $rsa->getPublicKey();
        $api_encrypted = '';
        $encrypted = '';

        if (openssl_public_encrypt($apiKEY, $encrypted, $publickey)) {
            $api_encrypted = base64_encode($encrypted);
        }
        return $api_encrypted;
    }

    function sendUSSDPUSH()
    {   
        try {
            //code...
            $sessionID = $this->GenerateSessionKey();
        } catch (\Throwable $th) {
            //throw $th;
            print_r($th->getMessage());
        } 

        $token =   $this->create_bearer_token($sessionID);
        print_r("\n\ntoekn ". $token);
        $data = array(
            'input_Amount' => 2030,
            'input_Country' => 'TZN',
            'input_Currency' => 'TZS',
            'input_CustomerMSISDN' => '255766303775',
            'input_ServiceProviderCode' => '000000',
            'input_ThirdPartyConversationID' => 'rerekf',
            'input_TransactionReference' => 'odfdferre',
            'input_PurchasedItemsDesc' => 'Test Two Item'
        );
        try {
            //code...
            $response = $this->client->request('POST',
                'https://openapi.m-pesa.com/sandbox/ipg/v2/vodacomTZN/c2bPayment/singleStage/',
                $data,
                ['headers' => [
                    'Authorization' => "Bearer {$token}"
                ]]
            );
        } catch (\Throwable $th) {
            //throw $th;
            print_r($th->getMessage());
        }
        
        print_r(
            json_decode($response->getBody())
        );
    }
}



$pesa = new Pesa();
// $pesa->GenerateSessionKey();
$pesa->sendUSSDPUSH();
