<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('session','encryption','form_validation','parser','email','image_lib','javascript','pagination','table',
'trackback','typography','user_agent','xmlrpc','SteamApi','cart');

$autoload['drivers'] = array();

$autoload['helper'] = array('typography','captcha','date','form','email','inflector','number','html','cookie','url','array','captcha',
'security','form_helper','string','text');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('Sess','Msg','User','Orders','Finance');
