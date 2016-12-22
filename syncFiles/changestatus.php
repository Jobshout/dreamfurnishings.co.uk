<?php
ini_set('display_errors',1);

include_once("../include/mongo_connection.php");
include_once("logging.php");

$log = new Logging();
$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "DreamFurnishings";
$mon_db= $conn->$dbname;

$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
if($uuid!=''){
	$result=array();	
	if($one_row= $mon_db->collectionToSync->findOne(array("uuid" => $uuid))){
		$update_entry=$mon_db->collectionToSync->update(array("uuid" => $uuid), array('$set' => array("sync_state" => 1)));
		$log->lwrite('Updated [collectionToSync]uuid successfully at line '.__LINE__);	//log message
		$result['Success']= "Updated successfully!";
	}else{
		$log->lwrite('No matching results found in [collectionToSync] collection  at line '.__LINE__);	//log message
		$result['Error']= "No record found !";
	}
	echo json_encode($result);
}else{
	$log->lwrite('Error: Request can\'t be processed because required parameters are missing at line '.__LINE__);	//log message
}
?>