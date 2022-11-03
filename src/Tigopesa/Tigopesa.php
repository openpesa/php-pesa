<?php

namespace Openpesa\Sdk\Tigopesa;

use GuzzleHttp\Client;
use Openpesa\Sdk\Contracts\ProviderInterface;
use Openpesa\Sdk\Tigopesa\Services\Collection;

class Tigopesa implements ProviderInterface
{

    public  const ACCESS_TOKEN_ENDPOINT = "/v1/oauth/generate/accesstoken?grant_type=client_credentials";
    public const PAYMENT_AUTHORIZATION_ENDPOINT = "/v1/tigo/payment-auth/authorize";
    private $baseUrl;
    private $options;
    private $client;

    public function __construct($options, $client)
    {
        foreach ([
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
        ] as $key) {
            if (!array_key_exists($key, $options)) {
                throw new \InvalidArgumentException("Missing option: $key");
            }
        }
        $this->options = $options;
        $this->client = $client ?: new Client();
    }

    public function authorize(array $data): bool
    {
        return true;
    }

    public function encrypt(array $data): bool
    {
        return true;
    }

    public function collection(): Collection
    {
        return new Collection($this->options, $this->client);
    }
}
