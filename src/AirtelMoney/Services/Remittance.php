<?php

declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\Services;

use Openpesa\Sdk\Contracts\Services\RemittanceInterface;

class Remittance extends RemittanceInterface
{
    function checkEligibility($phone)
    {
        $response = $this->client->request('POST', 'openapi/moneytransfer/v2/validate', ['json' => []]);
        return  json_decode((string)$response->getBody(), true);
    }

    public function sendCredit(): array
    {
        $body = [
            'amount' => 10,
            'channelName' => 'M******Y',
            'country' => 'KENYA',
            'currency' => 'KES',
            'extTRID' => 'random-txn-id',
            'msisdn' => '98*****21',
            'mtcn' => '5**21',
            'payerCountry' => 'MG',
            'payerFirstName' => 'Bob',
            'payerLastName' => 'Builder',
            'pin' => 'KYJExln8rZwb14G1K5UE5YF/lD7KheNUM171MUEG3/f/QD8nmNKRsa44UZkh6A4cR8****'
        ];
        $headers  = [
            'Authorization' => "Bearer $this->token",
        ];
        $response = $this->client->request(
            'POST',
            'openapi/moneytransfer/v2/credit',
            [
                'headers' => $headers,
                'json' => $body,
            ]
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function send($data): array
    {
        $headers = [
            'Authorization' => "Bearer $this->token",
        ];

        $payload = [
            'channelName' => 'M******Y',
            'country' => 'KENYA',
            'txnID' => '************',
            'pin' => 'KYJExln8rZwb14G1K5UE5YF/lD7KheNUM171MUEG3/f/QD8nmNKRsa44UZkh6A4cR8*'
        ];


        $response = $this->client->request(
            'POST',
            'openapi/moneytransfer/v2/refund',
            [
                'headers' => $headers,
                'json' => $payload,
            ]
        );

        return  json_decode((string)$response->getBody(), true);
    }

    public function refund()
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,

        ];

        $payload = array(
            'channelName' => 'M******Y',
            'country' => 'KENYA',
            'txnID' => '************',
            'pin' => 'KYJExln8rZwb14G1K5UE5YF/lD7KheNUM171MUEG3/f/QD8nmNKRsa44UZkh6A4cR8*'
        );


        $response = $this->client->request(
            'POST',
            'openapi/moneytransfer/v2/refund',
            [
                'headers' => $headers,
                'json' => $payload,
            ]
        );
        return  json_decode((string)$response->getBody(), true);
    }

    public function query($id)
    {
        $headers = ['Authorization' => "Bearer $this->token"];
        $payload = [];

        $response = $this->client->request(
            'POST',
            'openapi/moneytransfer/v2/checkstatus',
            [
                'headers' => $headers,
                'json' => $payload,
            ]
        );
        return  json_decode((string)$response->getBody(), true);
    }
}
