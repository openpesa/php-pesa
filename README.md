# Pesa SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/openpesa/pesa.svg?style=flat-square)](https://packagist.org/packages/openpesa/pesa)
[![Build Status](https://img.shields.io/travis/openpesa/php-pesa/master.svg?style=flat-square)](https://travis-ci.org/openpesa/php-pesa)
[![Quality Score](https://img.shields.io/scrutinizer/g/openpesa/php-pesa.svg?style=flat-square)](https://scrutinizer-ci.com/g/openpesa/pesa)
[![Total Downloads](https://img.shields.io/packagist/dt/openpesa/pesa.svg?style=flat-square)](https://packagist.org/packages/openpesa/pesa)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require openpesa/pesa
```

## Usage

``` php

require '../vendor/autoload.php';

use Openpesa\SDK\Forodha;

// Intiate with keys
$forodha = new Forodha([
            'api_key' => 'YOUR_API_KEY', // use 'sandbox' for development in the test environment
            'public_key' => 'PUBLIC_KEY', // use your sandbox app API key for development in the test environment
            'client_options' => [], //
        ]);

// Setup the input amount
$data = [
    'input_Amount' => '10000',
    'input_Country' => 'TZN',
    'input_Currency' => 'TZS',
    'input_CustomerMSISDN' => '255766303775',
    'input_ServiceProviderCode' => '000000',
    'input_ThirdPartyConversationID' => 'rerekf',
    'input_TransactionReference' => rand(),
    'input_PurchasedItemsDesc' => 'Test Two Item'
];

// Use the service
$result = $forodha->transact('c2b', $data);

var_dump($result);

```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email alphaolomi@gmail.com instead of using the issue tracker.

## Credits

- [Alpha Olomi](https://github.com/openpesa)
- [Ley](https://github.com/leyluj)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
