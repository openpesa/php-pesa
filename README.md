# Pesa SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/openpesa/pesa.svg?style=flat-square)](https://packagist.org/packages/openpesa/pesa)
[![Build Status](https://img.shields.io/travis/openpesa/pesa/master.svg?style=flat-square)](https://travis-ci.org/openpesa/pesa)
[![Quality Score](https://img.shields.io/scrutinizer/g/openpesa/pesa.svg?style=flat-square)](https://scrutinizer-ci.com/g/openpesa/pesa)
[![Total Downloads](https://img.shields.io/packagist/dt/openpesa/pesa.svg?style=flat-square)](https://packagist.org/packages/openpesa/pesa)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require openpesa/pesa
```

## Usage

``` php
use Openpesa\SDK\Pesa;

// Set the consumer key and consumer secret as follows
$username = 'YOUR_USERNAME'; // use 'sandbox' for development in the test environment
$apiKey   = 'YOUR_API_KEY'; // use your sandbox app API key for development in the test environment

// Get one of the services
$pesa       = new Pesa($public_key, $apiKey);

// Use the service
$result = $pesa->c2b($invoice_id, $phone_number, $amount, $reference_id, $shortcode);

print_r($result);
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
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).