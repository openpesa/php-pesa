<?php

declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

class Registration
{
    private string $id;
    private string $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->status = $data['status'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
