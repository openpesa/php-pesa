<?php
require '../vendor/autoload.php';

use Openpesa\SDK\Forodha;
use Openpesa\SDK\Tests\Fixture;

$f = new Forodha([
    'api_key' => '',
    'public_key' => Fixture::$publicKey,
    'auth_url' => Fixture::$authUrl,
    'client_options' => [],
]);


$t = $f->transact('c2b', Fixture::data());
var_dump($t);

