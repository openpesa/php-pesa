<?php
declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;


class Balance
{
    private float $balance;
    private string $currency;
    private string $account_status;

    public function __construct(array $data)
    {
        $this->balance = $data['balance'];
        $this->currency = $data['currency'];
        $this->account_status = $data['account_status'];
    }

    public function getAccountStatus(): string
    {
        return $this->account_status;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
