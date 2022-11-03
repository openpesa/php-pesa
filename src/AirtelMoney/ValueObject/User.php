<?php

declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney\ValueObject;

class User
{
    private string $dob;
    private string $first_name;
    private ?string $last_name = null;
    private string $grade;
    private bool $is_barred;
    private bool $is_pin_set;
    private string $msisdn;
    private Registration $registration;

    public function __construct(array $data)
    {
        $this->dob = $data['dob'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->grade = $data['grade'];
        $this->is_barred = boolval($data['is_barred']);
        $this->is_pin_set = boolval($data['is_pin_set']);
        $this->msisdn = $data['msisdn'];
        $this->registration = new Registration([
            'id' => $data['registration']['id'],
            'status' => $data['registration']['status']
        ]);
    }

    public function getDob(): string
    {
        return $this->dob;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function getGrade(): string
    {
        return $this->grade;
    }

    public function isIsBarred(): bool
    {
        return $this->is_barred;
    }

    public function isIsPinSet(): bool
    {
        return $this->is_pin_set;
    }

    public function getMsisdn(): string
    {
        return $this->msisdn;
    }

    public function getRegistration(): Registration
    {
        return $this->registration;
    }
}
