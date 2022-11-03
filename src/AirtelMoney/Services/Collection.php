<?php

namespace Openpesa\Sdk\AirtelMoney\Services;


use Openpesa\Sdk\Contracts\Services\CollectionInterface;

class Collection extends CollectionInterface
{

    public function send($amount, $phone, $id = null, $reference = null, $currency = null, $country = null)
    {
        $body = array(
            'reference'   => is_null($reference) ? random_bytes(8) : $reference,
            'subscriber'  => array(
                'country'  => is_null($country) ? $this->country : $country,
                'currency' => is_null($currency) ? $this->currency : $currency,
                'msisdn'   => $phone,
            ),
            'transaction' => array(
                'amount'   => $amount,
                'country'  => is_null($country) ? $this->country : $country,
                'currency' => is_null($currency) ? $this->currency : $currency,
                'id'       => is_null($id) ? random_bytes(8) : $id,
            ),
        );
        $headers = array(
            'X-Country'     => $this->country,
            'X-Currency'    => $this->currency,
            'Authorization' => 'Bearer ' . $this->token,
        );
        $response = $this->client->request(
            'POST',
            '/merchant/v1/payments/',
            array(
                'headers' => $headers,
                'json'    => $body,
            )
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function refund($id, $country = null, $currency = null, $callback = null)
    {
        $headers = array(
            'Content-Type'  => 'application/json',
            'X-Country'     => is_null($country) ? $this->country : $country,
            'X-Currency'    => is_null($currency) ? $this->currency : $currency,
            'Authorization' => 'Bearer ' . $this->token,
        );
        $body = array(
            'transaction' => array(
                'airtel_money_id' => $id,
            )
        );
        $response = $this->client->request(
            'POST',
            '/standard/v1/payments/refund',
            array(
                'headers' => $headers,
                'json'    => $body,
            )
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function query($id, $country = null, $currency = null, $callback = null)
    {

        $headers =  array(
            'Content-Type'  => 'application/json',
            'X-Country'     => is_null($country) ? $this->country : $country,
            'X-Currency'    => is_null($currency) ? $this->currency : $currency,
            'Authorization' => 'Bearer ' . $this->token,
        );
        $response = $this->client->request(
            'GET',
            '/standard/v1/payments/{$id}',
            array(
                'headers' => $headers,
                'json'    => array(
                    'transaction' => array(
                        'airtel_money_id' => $id,
                    ),
                ),
            )
        );
    }
}
