# Pesa SDK for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/openpesa/pesa.svg?style=flat-square&?include_prereleases)](https://packagist.org/packages/openpesa/pesa)
[![Build Status](https://img.shields.io/travis/openpesa/php-pesa/main.svg?style=flat-square)](https://travis-ci.org/openpesa/php-pesa)
[![codecov.io](https://img.shields.io/codecov/c/github/openpesa/php-pesa/main?style=flat-square)](https://codecov.io/github/openpesa/php-pesa)
[![Total Downloads](https://img.shields.io/packagist/dt/openpesa/pesa.svg?style=flat-square)](https://packagist.org/packages/openpesa/pesa)

The **Pesa SDK for PHP** makes it easy for developers to access [OpenAPI](https://openapiportal.m-pesa.com/) in their PHP code, and build robust applications and software using services like Customber 2 Bussiness, Query etc.

## Documentation

Take a look at the [API docs here](https://php-pesa.netlify.app/).

## Getting Started

1. **Sign up for OpenAPI Portal** – Before you begin, you need to
   sign up for an account and retrieve your credentials.

1. **Minimum requirements** – To run the SDK, your system will need to meet the
   [minimum requirements](https://php-pesa.netlify.app/docs/requirements.html), including having **PHP >= 7.1**.
   <!-- We highly recommend having it compiled with the cURL extension and cURL
   7.16.2+ compiled with a TLS backend (e.g., NSS or OpenSSL). -->
1. **Install the SDK** – Using [Composer] is the recommended way to install the
   Pesa SDK for PHP. The SDK is available via [Packagist] under the
   [`openpesa/php-pesa`](https://packagist.org/packages/openpesa/pesa) package. If Composer is installed globally on your system, you can run the following in the base directory of your project to add the SDK as a dependency:
   ```sh
   composer require openpesa/pesa
   ```
   Please see the
   [Installation section of the User Guide](https://php-pesa.netlify.app/docs/installation.html) for more
   detailed information about installing the SDK through Composer and other
   means.
1. **Using the SDK** – The best way to become familiar with how to use the SDK
   is to read the [User Guide](https://php-pesa.netlify.app/docs/guide.html). 
   
   <!-- The [Getting Started Guide](#) will help you become familiar with
   the basic concepts. -->


## Usage

### Quick Examples

```php

require 'vendor/autoload.php';

use Openpesa\SDK\Forodha;

// Intiate with credentials
$forodha = new Forodha([
            'api_key' => 'YOUR_API_KEY', 
            'public_key' => 'PUBLIC_KEY', 
            'client_options' => [], 
        ]);

// Setup the transaction 
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

// Execute 
$result = $forodha->transact('c2b', $data);

// Print results
var_dump($result);

```

For more example check [pesa-demo-example](https://github.com/openpesa/php-pesa/tree/develop/examples).

### Testing

```bash
composer test
```

## Opening Issues

If you have a feature requrest or you encounter a bug, please file an issue on [our issue tracker on GitHub](https://github.com/openpesa/php-pesa/issues).

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please review our [CONTRIBUTING](CONTRIBUTING.md) for details. 


### Security

If you discover any security related issues, please email [alphaolomi@gmail.com](mailto:alphaolomi@gmail.com) instead of using the issue tracker.

## Credits

-   [Alpha Olomi](https://github.com/openpesa)
-   [Ley](https://github.com/leyluj)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
