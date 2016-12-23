<?php
ini_set('max_execution_time', 300);
ini_set('display_errors',1);

//include_once("config.inc.php");
include_once("../include/mongo_connection.php");
include_once("logging.php");

$log = new Logging();
$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "DreamFurnishings";
$mon_db= $conn->$dbname;

$query_string=explode('&',$_SERVER['QUERY_STRING']);
$subObjectName="";
$subObjectValue="";
foreach($query_string as $qry){
	$temp_arr=explode('=',$qry);
	if($temp_arr[0]=='tablename'){
		$tablename=$temp_arr[1];
	}elseif($temp_arr[0]=='sub_table_name'){
		$subObjectName=$temp_arr[0];
		$subObjectValue=$temp_arr[1];
	}
	else{
		$index_col=$temp_arr[0];
		$index_val=$temp_arr[1];
	}
}

if(isset($tablename) && isset($index_col) && isset($index_val) && $tablename!='' && $index_col!='' && $index_val!=''){
	$result=array();
	$collection = $mon_db->$tablename;
	if(is_numeric($index_val)){
		$index_val=(int)$index_val;
	}
	$col_exists=$collection->find(array( $index_col => array( '$exists' => true ) ));
	if($col_exists->count()>0){	
		$log->lwrite('Connection established with '.$dbname.' database at line '.__LINE__);	//log message
		//echo json_encode(array( $index_col => $index_val ))."====";
		$data= $collection->find(array( $index_col => $index_val ));
		if($data->count()>0){
			$log->lwrite('Record found ['.$tablename.']'.$index_col.' : '.$index_val.' at line '.__LINE__);	//log message
			foreach($data as $row){
				if(isset($subObjectValue) && isset($row[$subObjectValue]) && $row[$subObjectValue]!=""){
					foreach($row[$subObjectValue] as $objrow){
						$result[]= $objrow;
					}
				}else{
					foreach($data as $row){
						$result[]= $row;
					}
				}
			}
			if(count($result)>0){
				$log->lwrite('Request processed successfully at line '.__LINE__);	//log message
			}
		}else{
			$log->lwrite('No matching results found in ['.$tablename.'] collection  at line '.__LINE__);	//log message
			$result['Error']= "No record found !!!";
		}			
	}
	else{
		$log->lwrite('Response: ['.$tablename.']'.$index_col.' field doesn\'t exists at line '.__LINE__);	//log message
		$result['Error']= "Field doesn't exists !!!";
	}
	echo json_encode($result);
}else{
	$log->lwrite('Error: Request can\'t be processed because required parameters are missing at line '.__LINE__);	//log message
}

?>