<?php
declare(strict_types=1);

namespace Openpesa\Sdk\Contracts\Services;

interface CollectionInterface extends ServiceInterface
{
    public function request(array $data): array;
    public function status(array $data): array;
    public function reverse(array $data): array;
    public function refund(array $data): array;
}
