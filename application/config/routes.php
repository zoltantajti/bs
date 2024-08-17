<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*Backend*/
$route['Api/getCustomerName']['post'] = 'Rest/getCustomerName';
$route['Api/getProductByCode']['post'] = 'Rest/getProductByCode';
$route['Api/updateProduct']['post'] = 'Rest/updateProduct';
$route['Api/addToCart']['post'] = 'Rest/addToCart';
$route['Api/getItems']['post'] = 'Rest/drawCartItems';
$route['Api/updateItem']['post'] = 'Rest/updateItem';
$route['Api/removeItem']['post'] = 'Rest/removeItem';
$route['Api/finishOrder']['post'] = 'Rest/finishOrder';
$route['Api/updateOrder']['post'] = 'Rest/updateOrder';
$route['Api/submitOrder']['post'] = 'Rest/submitOrder';
$route['Api/confirmOrder']['post'] = 'Rest/confirmOrder';
$route['Api/modifyOrderStatus']['post'] = 'Rest/modifyOrderStatus';
$route['Api/setGroupStatus']['post'] = 'Rest/setGroupStatus';

/*Frontend*/
$route['(:any)'] = 'Main/$1';
$route['(:any)/(:any)'] = 'Main/$1/$2';
$route['default_controller'] = 'Main';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
