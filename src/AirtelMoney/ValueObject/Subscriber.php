<?php
declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

class Subscriber
{
    private ?string $country = null;
    private ?string $currency = null;
    private string $msisdn;

    public function __construct(array $data)
    {
        $this->country = $data['country'] ?: null;
        $this->country = $data['currency'] ?: null;
        $this->msisdn = $data['msisdn'];
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getMsisdn(): string
    {
        return $this->msisdn;
    }
}
