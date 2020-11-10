<?php
require '../vendor/autoload.php';

use Openpesa\SDK\Pesa;
use Openpesa\SDK\Tests\Fixture;

$f = new Pesa([
    'api_key' => '',
    'public_key' => Fixture::$publicKey,
]);


$t = $f->c2b(Fixture::$data_c2b);
var_dump($t);

