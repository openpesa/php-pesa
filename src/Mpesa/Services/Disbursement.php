<?php

namespace Openpesa\Sdk\Mpesa\Services;

use Openpesa\Sdk\Contracts\Services\DisbursementInterface;

class Disbursement extends DisbursementInterface
{
    /**
     * Business to Customer (B2C)
     *
     * The B2C API Call is used as a standard business-to-customer
     *  funds disbursement. Funds from the business account’s wallet
     *  will be deducted and paid to the mobile money wallet of the customer.
     *  Use cases for the B2C includes:
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
}
