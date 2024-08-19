<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$acc = array();
if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "dayz24.local"){
	$acc['host'] = "localhost";
	$acc['user'] = "root";
	$acc['pass'] = "";
	$acc['db'] = 'viki_bs';
}else{
	$acc['host'] = "localhost";
	$acc['user'] = "clanwebh_wp170";
	$acc['pass'] = "Sm@!pq7z55";
	$acc['db'] = 'clanwebh_bscrm';
}


$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $acc['host'],
	'username' => $acc['user'],
	'password' => $acc['pass'],
	'database' => $acc['db'],
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
