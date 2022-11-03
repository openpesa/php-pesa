<?php

declare(strict_types=1);

namespace Openpesa\Sdk\Exceptions;

class HttpException extends \RuntimeException
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    public static function fromClientException($e): self
    {
        return new self(
            message: $e->getMessage(),
            code: $e->getCode(),
            previous: $e->getPrevious()
        );
    }
}
