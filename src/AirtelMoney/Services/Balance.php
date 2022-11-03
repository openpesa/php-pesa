<?php

namespace Openpesa\Sdk\AirtelMoney\Services;


class Balance
{
    public function balance(?string $currency)
    {
        $response = $this->client->request('GET', "'/standard/v1/users/balance'", [
            'headers' => [
                'X-Currency' => $currency ?: $this->configuration->getCurrency()
            ]
        ]);
        return json_decode((string)$response->getBody(), true);
    }
}
