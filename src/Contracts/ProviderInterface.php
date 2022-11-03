<?php
declare(strict_types=1);

namespace Openpesa\Sdk\Contracts;

use Openpesa\Sdk\Contracts\Services\ServiceInterface;

interface ProviderInterface
{
    public function authorize(array $data): bool;
    public function encrypt(array $data): bool;

    public function collection(): ServiceInterface;
    // public function disburse(): ServiceInterface;
    // public function remittance(): ServiceInterface;
    // public function customer(): ServiceInterface;
}
