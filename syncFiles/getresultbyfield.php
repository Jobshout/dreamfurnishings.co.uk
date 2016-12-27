<?php
include_once("config.php");
$result=array();

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){
	$log->lfile('logs/log_'.date("j-n-Y").'.txt');

	// write message to the log file
	$log->lwrite('------------------------------------------------------');		//log message

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
		}else{
			$index_col=$temp_arr[0];
			$index_val=$temp_arr[1];
		}
	}

	if(isset($tablename) && isset($index_col) && isset($index_val) && $tablename!='' && $index_col!='' && $index_val!=''){
	
		$collection = $mon_db->$tablename;
		if(is_numeric($index_val)){
			$index_val=(int)$index_val;
		}
		$col_exists=$collection->find(array( $index_col => array( '$exists' => true ) ));
		if($col_exists->count()>0){	
			$log->lwrite('Connection established with '.$dbname.' database at line '.__LINE__);	//log message
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
				$msgStr='No matching results found in ['.$tablename.'] collection  at line '.__LINE__;
				$log->lwrite($msgStr);	//log message
				$result['Error']= $msgStr;
			}			
		}
		else{
			$msgStr='Response: ['.$tablename.']'.$index_col.' field doesn\'t exists at line '.__LINE__;
			$log->lwrite($msgStr);	//log message
			$result['Error']= $msgStr;
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