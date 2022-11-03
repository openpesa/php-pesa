<?php

namespace Openpesa\Sdk\Tigopesa\Services;


use Openpesa\Sdk\Contracts\Services\CollectionInterface;

class Collection extends CollectionInterface
{
    public function request(array $data): array
    {

        $response = $this->client->request(
            'POST',
            "/v1/tigo/payment-auth/authorize",
            [
                'headers' => [
                    'accesstoken' => $data['issuedToken'],
                    'cache-control' => 'no-cache',
                    'content-type' => 'application/json',
                ],
                'body' => $this->createPaymentAuthJson(
                    $data['amount'],
                    $data['referenceId'],
                    $data['customerFirstName'],
                    $data['customerLastName'],
                    $data['customerEmail']
                )
            ]
        );

        return json_decode((string)$response->getBody(), true);
    }

    public function createPaymentAuthJson(
        $amount,
        $referenceId,
        $customerFirstName,
        $customerLastName,
        $customerEmail
    ) {

        $paymentJson = '{
  "MasterMerchant": {
    "account": "' . $this->options['account_number'] . '",
    "pin": "' . $this->options['pin'] . '",
    "id": "' . $this->options['account_id'] . '"
  },
  "Merchant": {
    "reference": "",
    "fee": "0.00",
    "currencyCode": ""
  },
  "Subscriber": {
    "account": "",
    "countryCode": "255",
    "country": "TZA",
    "firstName": "' . $customerFirstName . '",
    "lastName": "' . $customerLastName . '",
    "emailId": "' . $customerEmail . '"
  },
  "redirectUri":" ' . $this->options['redirect_url'] . '",
  "callbackUri": "' . $this->options['callback_url'] . '",
  "language": "' . $this->options['lang'] . '",
  "terminalId": "",
  "originPayment": {
    "amount": "' . $amount . '",
    "currencyCode": "' . $this->options['currency_code'] . '",
    "tax": "0.00",
    "fee": "0.00"
  },
  "exchangeRate": "1",
  "LocalPayment": {
    "amount": "' . $amount . '",
    "currencyCode": "' . $this->options['currency_code'] . '"
  },
  "transactionRefId": "' . $referenceId . '"
}';
        return $paymentJson;
    }


    public function authorize()
    {

        $data = [
            'client_id' => $this->options['client_id'],
            'client_secret' => $this->options['secret']
        ];

        $response = $this->client->request(
            'POST',
            '/v1/oauth/generate/accesstoken?grant_type=client_credentials',
            [
                'form_params' => $data
            ]
        );

        return json_decode((string)$response->getBody(), true);
    }
}
