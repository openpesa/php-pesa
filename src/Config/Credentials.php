<?php

declare(strict_types=1);

namespace Devscast\AirtelMoney\Data;

use Webmozart\Assert\Assert;

/**
 * Class Credentials
 * @package Devscast\AirtelMoney\Data
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class Credentials
{
    /**
     * Type of token
     */
    private string $token_type;

    /**
     * Received token of the user
     */
    private string $access_token;

    /**
     * Expiry time of the access-token received
     */
    private string $expires_in;

    /**
     * Credentials constructor.
     * @param string $token_type
     * @param string $access_token
     * @param string $expires_in
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(string $token_type, string $access_token, string $expires_in)
    {
        Assert::stringNotEmpty($token_type);
        Assert::stringNotEmpty($access_token);
        Assert::stringNotEmpty($expires_in);

        $this->token_type = $token_type;
        $this->access_token = $access_token;
        $this->expires_in = $expires_in;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getExpiresIn(): string
    {
        return $this->expires_in;
    }
}
