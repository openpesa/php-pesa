<?php
declare(strict_types=1);

namespace Openpesa\Sdk\AirtelMoney;

use Openpesa\Sdk\Config\Configuration;
use Openpesa\Sdk\AirtelMoney\Services;
use Openpesa\Sdk\Client;
use Openpesa\Sdk\Contracts\ProviderInterface;

class AirtelMoney implements ProviderInterface
{
    protected Configuration $configuration;
    protected Client $client;

    /**
     * IMT Remittance error codes
     * These are the common error codes,
     * which may be returned when you communicate with Airtel for IMT Remittance.
     */
    public const IMT_ERROR_CODES = [
        '521002' => 'Invalid Msisdn Length',
        '1703' => 'Account number for the service is rejected',
        '60011' => 'Threshold count for Payer reached for the day'
    ];

    /**
     * Group Merchant error codes
     * These error codes may be returned when you interact for Group Merchant transactions.
     */
    public const MERCHANT_ERROR_CODES = [
        'ESB000001' => 'Something went wrong',
        'ESB000004' => 'An error occurred while initiating the payment',
        'ESB000008' => 'Field validation',
        'ESB000011' => 'Failed',
        'ESB000014' => 'An error occurred while fetching the transaction status',
        'ESB000033' => 'Invalid MSISDN Length. MSISDN Length should be %s',
        'ESB000034' => 'Invalid Country Name',
        'ESB000035' => 'Invalid Currency Code',
        'ESB000036' => 'Invalid MSISDN Length. MSISDN Length should be %s and should start with 0',
        'ESB000039' => 'Vendor is not configured to do transaction in the country %s'
    ];

    public const OAUTH_URL = '%s/oauth2/token';

    public const BASE_URL_PROD = 'https://openapiuat.airtel.africa';
    public const BASE_URL_SANDOX =  'https://openapi.airtel.africa';

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
        $client = Client::create();
    }

    public function collection()
    {
        return new Services\Collection($this->configuration);
    }

    public function disbursement()
    {
        return new Services\Disbursement($this->configuration);
    }

}
