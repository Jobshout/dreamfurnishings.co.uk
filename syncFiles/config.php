<?php
ini_set('display_errors',1);
date_default_timezone_set("Europe/London");
include_once("../include/mongo_connection.php");
include_once("logging.php");
$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "DreamFurnishings";
$mon_db= $conn->$dbname;
$db=$mon_db;

function secure_authentication ($guid) {	
	global $mon_db;
	if($secureData= $mon_db->authentication_token->findOne(array("name" => "security-token", "active" => true, "guid" => $guid))){
		return true;
	}else{
		return false;
	}
}
$log = new Logging();

//mongoCRUD FILE
require_once('../include/MongoCRUD.php');

$mongoCRUDClass = new MongoCRUD;
$mongoCRUDClass->collectionsAllowedArr=array("Products", "categories", "collectionToSync", "orders", "web_content", "Tokens");

?>