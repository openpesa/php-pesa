<?php
declare(strict_types=1);

namespace Openpesa\Sdk\Contracts\Services;

interface RemittanceInterface extends ServiceInterface
{
    public function send(array $data): array;
    public function status(array $data): array;
    public function reverse(array $data): array;
    public function refund(array $data): array;
}
