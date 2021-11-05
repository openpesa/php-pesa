<?php

namespace Openpesa\SDK;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

/**
 * @package Openpesa\SDK
 */
class Airtel
{

    /**
     * @var string
     */
    private $airtel_config;

    public function __construct()
    {
        $airtel_config = include('Config.php');
        $this->airtel_config = $airtel_config;
        if(!key_exists("airtelmoney",$airtel_config)) throw new InvalidArgumentException("gateway doesnot exist , check spelling");
      return  $live_mode = $this->airtel_config['airtelmoney']['live']['BASE_DOMAIN'];
        return $live_mode_json = json_encode($live_mode);
        // print_r( $live_mode_json);


    }





}

$obj = new Airtel();
print($obj->__construct());

