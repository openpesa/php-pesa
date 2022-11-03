<?php

declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

class UssdPaymentTransaction
{
    private string $airtel_money_id;
    private ?string $id = null;
    private ?string $message = null;
    private string $status;

    public function __construct(array $data)
    {
        $this->airtel_money_id = $data['airtel_money_id'];
        $this->message = $data['message'] ?: null;
        $this->status = $data['status'];
        $this->id = $data['id'] ?: null;
    }

    public function getAirtelMoneyId(): string
    {
        return $this->airtel_money_id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
