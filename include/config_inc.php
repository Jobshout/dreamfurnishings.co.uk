<?php
date_default_timezone_set("Europe/London");
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
define("PRODUCT_IMAGE_DIRECTORY","images/products/");

require_once('include/functions.php');

//mongo connection file
require_once('include/mongo_connection.php');

//mongoCRUD FILE
require_once('include/MongoCRUD.php');

$mongoCRUDClass = new MongoCRUD;
$mongoCRUDClass->collectionsAllowedArr=array("Contacts", "authentication_token", "collectionToSync", "email_queue", "orders", "web_content", "web_enquiries");

$getAddrStr=get_token_value('dreamfurnishing-address');
if($getAddrStr==""){
	$getAddrStr="58 Green Street, Forest Gate,<br>London, UK, E7 8BZ.<br>";
}
define("ADDRESS",$getAddrStr);

define("SEOFRIENDLYFLAG",true);
define("HIDETAX", true);
?>