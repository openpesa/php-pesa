# Customer 2 Bussines Transaction Fundamentals

## How to transact from customer to business

In this course, we will cover what you need to transact from customers to businesses using PHP.

First set up the application in the portal

When building apps on the sandbox application, Using the sandbox API which allows us to test our application logic without incurring any costs.

At the bottom left corner of the screen there’s a little phone icon, click it and enter your phone number. This will be the test number you’ll be using and is the number we will send the airtime to. Ensure that you’ve edited the phone number variable we wrote earlier to the phone number you’re using on the simulator.

we will import the required libraries which have already been preinstalled.

Here’s the code that does this:


```php
require 'vendor/autoload.php';
use Openpesa\SDK\Forodha;
```

The above code imports the Pesa SDK for us.

Next, we set our app credentials.



```php
$apiKey     = "your_API_key";
$publicKey  = "your_public_key";
```

These are the credentials that we use to authenticate requests to the OpenAPI service.

Now we are going to initialize the Pesa SDK.

```php
$forodha = new Forodha([
    'api_key' => $apiKey,
    'public_key' => $publicKey,
]);

```

We have just required the Forodha module into our app and assigned it to the `$forodha` variable. We initialize it with our `$api_key` and `$public_key` which will be used to make authenticated calls to the airtime service. Easy right?

Now let’s set up our transaction.


```php

$data = [
    'input_Amount' => 5000,
    'input_CustomerMSISDN' => '000000000001',
    'input_Country' => 'TZN',
    'input_Currency' => 'TZS',
    'input_ServiceProviderCode' => '000000',
    'input_TransactionReference' => 'T12344Z',
    'input_ThirdPartyConversationID' => '1e9b774d1da34af78412a498cbc28f5d',
    'input_PurchasedItemsDesc' => 'Test Three Item'
];

```

In order to transact from customer to business, you need the following.

- An Amount
- A CustomerMSISDN
- Country
- Currency
- Service Provider Code
- Transaction Reference
- Purchased Items Desc
- ThirdParty Conversation ID

Next up we have a function to execute the transaction right below the `// Execute transaction` line.


```php
try {
     // Execute transaction
    $result = $forodha->transact('c2b', $data);
    print_r($result);
} catch (Throwable $th) {
    echo $th->getMessage();
}

```

If the code works and the transaction is successful, it will print the response onto the console logs. If it’s not successful, it will print out an error instead.

You’re all set!



## Example Full 


```php
<?php
require 'vendor/autoload.php';

use Openpesa\SDK\Forodha;

$publicKey = 'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEArv9yxA69XQKBo24BaF/D+fvlqmGdYjqLQ5WtNBb5tquqGvAvG3WMFETVUSow/LizQalxj2ElMVrUmzu5mGGkxK08bWEXF7a1DEvtVJs6nppIlFJc2SnrU14AOrIrB28ogm58JjAl5BOQawOXD5dfSk7MaAA82pVHoIqEu0FxA8BOKU+RGTihRU+ptw1j4bsAJYiPbSX6i71gfPvwHPYamM0bfI4CmlsUUR3KvCG24rB6FNPcRBhM3jDuv8ae2kC33w9hEq8qNB55uw51vK7hyXoAa+U7IqP1y6nBdlN25gkxEA8yrsl1678cspeXr+3ciRyqoRgj9RD/ONbJhhxFvt1cLBh+qwK2eqISfBb06eRnNeC71oBokDm3zyCnkOtMDGl7IvnMfZfEPFCfg5QgJVk1msPpRvQxmEsrX9MQRyFVzgy2CWNIb7c+jPapyrNwoUbANlN8adU1m6yOuoX7F49x+OjiG2se0EJ6nafeKUXw/+hiJZvELUYgzKUtMAZVTNZfT8jjb58j8GVtuS+6TM2AutbejaCV84ZK58E2CRJqhmjQibEUO6KPdD7oTlEkFy52Y1uOOBXgYpqMzufNPmfdqqqSM4dU70PO8ogyKGiLAIxCetMjjm6FCMEA3Kc8K0Ig7/XtFm9By6VxTJK1Mg36TlHaZKP6VzVLXMtesJECAwEAAQ==';
$apiKey = '';

$forodha = new Forodha([
    'api_key' => $apiKey,
    'public_key' => $publicKey,
]);


$data = [
    'input_Amount' => 5000,
    'input_CustomerMSISDN' => '000000000001',
    'input_Country' => 'TZN',
    'input_Currency' => 'TZS',
    'input_ServiceProviderCode' => '000000',
    'input_TransactionReference' => 'T12344Z',
    'input_ThirdPartyConversationID' => '1e9b774d1da34af78412a498cbc28f5d',
    'input_PurchasedItemsDesc' => 'Test Three Item'
];


try {
    $result = $forodha->transact('c2b', $data);
    print_r($result);
} catch (Throwable $th) {
    echo $th->getMessage();
}


```

Happy Coding!! 😀
