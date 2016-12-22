<?php
ini_set('display_errors',1);

include_once("../include/mongo_connection.php");
include_once("logging.php");

$log = new Logging();
$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$tablename="collectionToSync";

$result=array();
$collection = $db->$tablename;

$data= $collection->find(array("table_name"=> "web_content","sync_state" => 0))->limit(1000);
if($data->count()>0){
	foreach($data as $row){
		$result[]= $row;
	}
	if(count($result)>0){
		$log->lwrite('Request processed successfully for ['.$tablename.'] collection at line '.__LINE__);	//log message
	}
}else{
	$log->lwrite('No matching results found in ['.$tablename.'] collection  at line '.__LINE__);	//log message
	$result['Error']= "No record found !!!";
}
echo json_encode($result);
?>

