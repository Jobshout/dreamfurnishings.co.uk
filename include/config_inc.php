<?php

//ini_set('max_execution_time', 300);

$isSecure = false;
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $isSecure = true;
}elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $isSecure = true;
}

$HTTPIPAddrStr = isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : $_SERVER["REMOTE_ADDR"];

$REQUEST_PROTOCOL = $isSecure ? 'https' : 'http';
define('SITE_PATH', $REQUEST_PROTOCOL.'://'.$_SERVER['HTTP_HOST']);

define('SITE_WS_PATH', SITE_PATH.'/');
define('BACKEND_PATH', 'https://dev-billing.tenthmatrix.co.uk/hit/');
define("SITE_ID",1);
define("USER_IP_ADDRESS",$HTTPIPAddrStr);
define("SITE_NAME","Dream Furnishing");
define("CURRENCY","&pound;");

require_once('include/functions.php');

//mongo connection file
require_once('include/mongo_connection.php');

switch ($_SERVER['SERVER_NAME']) {
    case "dev.dreamfurnishings.co.uk":
	define("PRODUCT_DIR","https://dev-billing.tenthmatrix.co.uk");
	define("ADMIN_EMAIL","balinder.walia@gmail.com");
	define("ADMIN_CC_EMAIL","");
	define("ADMIN_BB_EMAIL","nehak189@gmail.com");
    break;
        
    case "staging.dreamfurnishings.com":
	define("PRODUCT_DIR","https://dev-billing.tenthmatrix.co.uk");
	define("ADMIN_EMAIL","balinder.walia@gmail.com");
	define("ADMIN_CC_EMAIL","");
	define("ADMIN_BB_EMAIL","nehak189@gmail.com");
    break;
        
    default:
	case "dev.dreamfurnishings.com":
	define("PRODUCT_DIR","https://crm.tenthmatrix.co.uk");
	define("ADMIN_EMAIL","balinder.walia@gmail.com");
	define("ADMIN_CC_EMAIL","");
	define("ADMIN_BB_EMAIL","nehak189@gmail.com");
    break;
}

$getAddrStr=get_token_value('dreamfurnishing-address');
if($getAddrStr==""){
	$getAddrStr="58 Green Street, Forest Gate,<br>London, UK, E7 8BZ.<br>";
}
define("ADDRESS",$getAddrStr);

define("SEOFRIENDLYFLAG",true);

?>