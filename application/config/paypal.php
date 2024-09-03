<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MODE') OR define('MODE', 'sandbox');
if(MODE == "sandbox"){
    $config['client_id'] = '***';
    $config['client_secret'] = '***';
}elseif(MODE == "live"){
    $config['client_id'] = '**';
    $config['client_secret'] = '**';
};
$config['settings'] = array('mode' => MODE,'http.ConnectionTimeOut' => 30,'log.LogEnabled' => true,'log.FileName' => APPPATH . 'logs/paypal.log','log.LogLevel' => 'FINE');
