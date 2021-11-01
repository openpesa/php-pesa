<?php

namespace Openpesa\SDK;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

/**
 * @package Openpesa\SDK
 */
class Pesa
{
    private $options;
    private $client;
    private $gateway;

        public function __construct(array $gateways,array $options, $client = null)
        {
            if (!key_exists('api_key', $options)) throw new  InvalidArgumentException("api_key is required");
            if (!key_exists('public_key', $options)) throw new  InvalidArgumentException("public_key is required");
            if (!key_exists('gateway', $options)) throw new  InvalidArgumentException(" gateway  is required");

                $options['client_options'] = $options['client_options'] ?? [];
                $options['persistent_session'] = $options['persistent_session'] ?? false;

                $options['service_provider_code'] = $options['service_provider_code'] ?? null;
                $options['country'] = $options['country'] ?? null;
                $options['currency'] = $options['currency'] ?? null;
                $this->gateway = $options['gateway'] ?? null;


                $this->options = $options;
                $this->client = $this->makeClient($options, $client);
        }


        /**
         * @param mixed $options
         * @param null $client
         *
         * @return Client
         */
        private function makeClient($options, $client = null): Client
        {
            $configs = include('config.php');
            if(!key_exists($this->gateway,$configs)) throw new InvalidArgumentException("gateway doesnot exist , check spelling");
            if($this->gateway==="tigopesa"){

                $tigopesa = new Mpesa($this->options, $this->client);
                return  $tigopesa;
            }
            else if($this->gateway==="mpesa"){
                $mpesa = new Mpesa($this->options, $this->client);

                return $mpesa;
            }
            if($this->gateway==="airtelmoney"){
                $airtelmoney = new Mpesa($this->options, $this->client);

                return $airtelmoney ;
            }
        }


        /**
         * @param mixed $options
         * get response in same json format
         * @return json
         */
        public function ProcessResponse($options){
            // TO DO

        }
}
