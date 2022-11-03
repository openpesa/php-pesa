<?php

namespace Osen\Airtel;

use GuzzleHttp\Client;


class Service
{
    public string $client_id;
    public string $client_secret;
    protected Client $client;
    protected string $token;
    protected string $country    = 'KE';
    protected string $currency   = 'KES';
    protected string $pin        = '';
    protected string $public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCkq3XbDI1s8Lu7SpUBP+bqOs/MC6PKWz
    6n/0UkqTiOZqKqaoZClI3BUDTrSIJsrN1Qx7ivBzsaAYfsB0CygSSWay4iyUcnMVEDrNVO
    JwtWvHxpyWJC5RfKBrweW9b8klFa/CfKRtkK730apy0Kxjg+7fF0tB4O3Ic9Gxuv4pFkbQ
    IDAQAB';

    public function __construct(array $options = [])
    {
        $this->client_id     = $options['client_id'];
        $this->client_secret = $options['client_secret'];
        $this->public_key    = $options['public_key'];
        $this->country       = $options['country'];
        $this->currency      = $options['currency'];
        $this->client        = new Client(
            array(
                'base_uri' => $options['env'] == 'staging'
                    ? 'https://openapiuat.airtel.africa/'
                    : 'https://openapi.airtel.africa/',
            )
        );
    }


    public function authorize($token = null)
    {
        $body = array(
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type'    => 'client_credentials',
        );
        $response = $this->client->request(
            'POST',
            '/auth/oauth2/token',
            array(
                'headers' => [],
                'json'    => $body,
            )
        );
        $this->token = json_decode((string)$response->getBody(), true)['access_token'];
    }

    public function encrypt($data)
    {

        $publicKey = openssl_pkey_get_public(array($this->public_key, ''));
        if (!$publicKey) {
            throw new \RuntimeException('Unable to load public key');
        }
        if (!openssl_public_encrypt($data, $encrypted, $publicKey)) {
            throw new \RuntimeException('Unable to encrypt data');
        }

        return base64_encode($encrypted);
    }



    public function userEnquiry($phone)
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'X-Country'     => $this->country,
            'X-Currency'    => $this->currency,
            'Authorization' => 'Bearer ' . $this->token,
        );


            $response = $this->client->request(
                'GET',
                "/standard/v1/users/{$phone}",
                array(
                    'headers' => $headers,
                    'json'    => [],
                )
            );
            return  json_decode((string)$response->getBody(), true);
    }
}
