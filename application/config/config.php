<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$server = $_SERVER['HTTP_HOST'];
if($server == "localhost"){ 
	$host = "localhost/bs"; 
}else{ 
	$host = $server; 
};
$config['base_url'] = ((@$_SERVER['HTTPS'] == "on") ? 'https://' : 'http://') . $host;
$config['index_page'] = '';
$config['uri_protocol']	= 'REQUEST_URI';
$config['url_suffix'] = '';
$config['language']	= 'english';
$config['charset'] = 'UTF-8';
$config['enable_hooks'] = TRUE;
$config['subclass_prefix'] = 'MY_';
$config['composer_autoload'] = FCPATH . 'vendor/autoload.php';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';
$config['allow_get_array'] = TRUE;
switch (ENVIRONMENT)
{
	case 'development': $config['log_threshold'] = 4; break;
	case 'testing':  $config['log_threshold'] = 2; break;
	case 'production': $config['log_threshold'] = 0; break;
}
$config['log_path'] = '';
$config['log_file_extension'] = '';
$config['log_file_permissions'] = 0644;
$config['log_date_format'] = 'Y-m-d H:i:s';
$config['error_views_path'] = '';
$config['cache_path'] = '';
$config['cache_query_string'] = FALSE;
$config['encryption_key'] = 'c35ee6d2fa1bd7e506378d3b92a3a6bbb4e15ef6c6fbf8e1a1039d069e8e161f';
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_samesite'] = 'Lax';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;
$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;
$config['cookie_samesite'] 	= 'Lax';
$config['standardize_newlines'] = FALSE;
$config['global_xss_filtering'] = TRUE;
$config['csrf_protection'] = false;
$config['csrf_token_name'] = 'csrf';
$config['csrf_cookie_name'] = 'c_csrf';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();
$config['compress_output'] = FALSE;
$config['time_reference'] = 'local';
$config['rewrite_short_tags'] = FALSE;
$config['proxy_ips'] = '';

$config['javascript_location'] = './assets/js/';
$config['csrf_exclude_uris'] = array(
	"doexam/OAK644"
);