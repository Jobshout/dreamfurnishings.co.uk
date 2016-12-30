<?php
include_once("config.php");
$result=array();

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){
	
	$log->lfile('logs/log_'.date("j-n-Y").'.txt');

	// write message to the log file
	$log->lwrite('------------------------------------------------------');		//log message

	$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
	if($uuid!=''){
		if($one_row = $mongoCRUDClass->db_findone("collectionToSync", array("uuid" => $uuid))){
			$update_entry=$mongoCRUDClass->db_update("collectionToSync", array("uuid" => $uuid), array("sync_state" => 1));
			$msgStr='Updated [collectionToSync]uuid successfully at line '.__LINE__;
			$log->lwrite($msgStr);	//log message
			$result['Success']= $msgStr;
		}else{
			$msgStr='No matching results found in [collectionToSync] collection  at line '.__LINE__;
			$result['Error']= $msgStr;
			$log->lwrite($msgStr);	//log message
		}
	}else{
		$msgStr='Error: Request can\'t be processed because required parameters are missing at line '.__LINE__;
		$result['Error']= $msgStr;
		$log->lwrite($msgStr);	//log message
	}
}else{
	$msgStr='Error: Request can\'t be processed because of security reasons at line '.__LINE__;
	$result['Error']= $msgStr;
	$log->lwrite($msgStr);	//log message
}
echo json_encode($result);
?>