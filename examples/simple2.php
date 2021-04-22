<?php
require '../vendor/autoload.php';

use Openpesa\SDK\Pesa;
use Openpesa\SDK\Tests\Fixture;

$f = new Pesa([
    'api_key' => '',
    'public_key' => Fixture::$publicKey,
    'country' => 'TZN',
    'currency' => 'TZS',
    'service_provider_code' => '000000',
]);


// $t = $f->getSession();

$t = $f->c2b(Fixture::dataMin());
var_dump($t);
