<?php

namespace Openpesa\Sdk\AirtelMoney\Services;

use Openpesa\Sdk\Contracts\Services\DisbursementInterface;

class Disbursement extends DisbursementInterface
{

    public function send($phone, $amount, $reference, $id = null, $currency = null, $country = null, $callback = null)
    {

        $response = $this->client->request(
            'POST',
            '/standard/v1/disbursements/',
            [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Accept'        => '*/*',
                    'X-Country'     => $country ?: $this->country,
                    'X-Currency'    => $currency ?: $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ],
                'json'    => [
                    'payee'       => [
                        'msisdn' => $phone,
                    ],
                    'reference'   => $reference,
                    'pin'         => $this->pin,
                    'transaction' => [
                        'amount' => $amount,
                        'id'     => $id ?: random_bytes(8),
                    ],
                ],
            ]
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function refund($id)
    {

        $response = $this->client->request(
            'POST',
            '/standard/v1/disbursements/refund',
            array(
                'headers' => array(
                    'Content-Type'  => 'application/json',
                    'Accept'        => '*/*',
                    'X-Country'     => $this->country,
                    'X-Currency'    => $this->currency,
                    'Authorization' => 'Bearer ' . $this->token,
                ),
                'json'    => array(),
            )
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function query($id)
    {
        $response = $this->client->request(
            'GET',
            '/standard/v1/disbursements/{id}',
            [
                'headers' => [
                    'Authorization' => "Bearer $this->token"
                ],
                'json'    => [],
            ]
        );
        return  json_decode((string)$response->getBody(), true);
    }
}
