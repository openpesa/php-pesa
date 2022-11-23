<?php

namespace Openpesa\Sdk\Mpesa\Services;


use Openpesa\Sdk\Contracts\Services\CollectionInterface;

class Collection extends CollectionInterface
{


    /**
     * Customer to Business (C2B)
     *
     * The C2B API call is used as a standard customer-to-business transaction.
     * Funds from the customer’s mobile money wallet will be deducted and be
     * transferred to the mobile money wallet of the business. To authenticate and
     * authorize this transaction, M-Pesa Payments Gateway will initiate
     * a USSD Push message to the customer to gather and verify the mobile money PIN number.
     * This number is not stored and is used only to authorize the transaction.
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



}
