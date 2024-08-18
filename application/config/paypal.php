<?php
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MODE') OR define('MODE', 'sandbox');
if(MODE == "sandbox"){
    $config['client_id'] = 'ASIwIzG4yDVOoATQgV6FFbyefiAEzsk7sj06mo9jbxLguNgqn958ZyeE9NGZ-mOS_TejG2LB3hvtNZUB';
    $config['client_secret'] = 'ENRCzaqKRPwdIaPkUWhKtR0KClTTULOYJcOyDquOPgZcBU-7Lh_s26EdsdR7DhBYwkMeEtjmJmOGERzR';
}elseif(MODE == "live"){
    $config['client_id'] = 'ASH72Ngtz9FdX_zFhOmZWSYnpCSzb_WgOvISPnCKoWlrV-ojvWxm0B5tBl_Ge1sqqLSPh9RCzABnfnvS';
    $config['client_secret'] = 'EL67zydAiQWDVDyA1ryOKG1uZA4AlxKojDhvFayGSRdPlNh4c55uzf1dStwR618E18gksLka0HTyusU0';
};
$config['settings'] = array('mode' => MODE,'http.ConnectionTimeOut' => 30,'log.LogEnabled' => true,'log.FileName' => APPPATH . 'logs/paypal.log','log.LogLevel' => 'FINE');