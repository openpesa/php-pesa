# Tigopesa

## Usage:

```php
<?php
$igo = new Tigopesa([
    'clientId' => 'your-client-id',
    'secret' => 'consumer secret',
    'apiUrl' => 'your provided tigopesa api url',
    'pin' => 'your provided tigopesa pin number',
    'accountNumber' => 'your provided tigopesa account number',
    'accountId' => 'your provided tigopesa account id',
    'appUrl' => 'your app url',
    'redirectUrl' => 'your redirect url',
    'callbackUrl' => 'your callback url',
    'currencyCode' => 'TZS', // currency put TZS for Tanzanian Shillings
    'lang' => 'sw', // language code en for English and sw for Swahili
]);

$refId = generateRandomString(); // sample: TIGOPESA12KHJGKJHKJHG

$response = $tigo->c2b([
    "customerFirstName" => "James",
    "customerFirstName" => "Hilton",
    "customerEmail" => "hameshitlon@gmail.com",
    "amount" => 4000,
    "referenceId" => "123456789", // unique reference id
]);

var_dump($response);
```

## Response:

```php

```

## Misc

