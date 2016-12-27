<?php
include_once("config.php");
$result=array();

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){
	
	$log->lfile('logs/log_'.date("j-n-Y").'.txt');

	// write message to the log file
	$log->lwrite('------------------------------------------------------');		//log message

	$tablename="collectionToSync";
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
		$msgStr='No matching results found in ['.$tablename.'] collection  at line '.__LINE__;
		$log->lwrite($msgStr);	//log message
		$result['Error']= $msgStr;
	}
}else{
	$msgStr='Error: Request can\'t be processed because of security reasons at line '.__LINE__;
	$result['Error']= $msgStr;
	$log->lwrite($msgStr);	//log message
}
echo json_encode($result);
?>

