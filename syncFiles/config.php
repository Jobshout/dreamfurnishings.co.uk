<?php
ini_set('display_errors',1);

include_once("../include/mongo_connection.php");
include_once("logging.php");
$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "DreamFurnishings";
$mon_db= $conn->$dbname;

function secure_authentication ($guid) {	
	global $mon_db;
	if($secureData= $mon_db->authentication_token->findOne(array("name" => "security-token", "active" => true, "guid" => $guid))){
		return true;
	}else{
		return false;
	}
}
$log = new Logging();
?>