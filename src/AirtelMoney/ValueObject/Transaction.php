<?php
declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

class Transaction
{

    private string $id;
    private float $amount;
    private ?string $currency = null;
    private ?string $country = null;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->amount = $data['amount'];
        $this->currency = $data['currency'] ?: null;
        $this->country = $data['country'] ?: null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}
