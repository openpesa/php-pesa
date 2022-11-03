<?php
declare(strict_types=1);

namespace Openpesa\Sdk\Config;

use Webmozart\Assert\Assert;

/** */
class Configuration
{
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function create(array $data): Configuration
    {
        return new Configuration($data);
    }
}
