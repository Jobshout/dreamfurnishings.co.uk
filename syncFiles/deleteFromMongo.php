<?php
include_once("config.php");

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){
	$log->lfile('logs/log_'.date("j-n-Y").'.txt');

	// write message to the log file
	$log->lwrite('------------------------------------------------------');		//log message
	$tablename=isset($_GET['tablename']) ? $_GET['tablename'] : "";
	
	if($tablename!="" && $dbname!=""){
		$file_path='php://input';
		$collection = $mon_db->$tablename;	
		$log->lwrite('Established connection with '.$dbname.' Database at line '.__LINE__); //log message
	
		$data= file_get_contents($file_path);
		if($data!=""){
			$log->lwrite('Retrieved data from server at line '.__LINE__); //log message
			$arr_tbl_data=json_decode($data);
		
			if(count($arr_tbl_data)>0){
				$log->lwrite('Decoded the json successfully at line '.__LINE__); //log message
				foreach($arr_tbl_data as $row){
					if(isset($_GET['updatecol']) && $_GET['updatecol']!=""){
						$updatecol=$_GET['updatecol'];
						echo "Record with ".$updatecol." : ".$row->$updatecol." has been ";
					
						$log->lwrite("Find record with [".$tablename."]".$updatecol." : ".$row->$updatecol);	//log message
						$exist = $collection->find(array($updatecol => $row->$updatecol));
						$num_exist = $exist->count();
						if($num_exist>0){
							$collection->remove(array($updatecol => $row->$updatecol));
							$log->lwrite('Deleted record successfully at line '.__LINE__); //log message
							echo "deleted";
						}else{
							$log->lwrite('No such record found in ['.$tablename.'] at line '.__LINE__); //log message
							echo "not found";
						}
					}else{
						$log->lwrite('Error: Search parameters are missing at line '.__LINE__);	//log message
						echo "error";
					}
 				echo ": ".date('d/m/Y H:i:s')."<br/>";
				}
			}else{
				$log->lwrite('Error: No data retrieved from server(empty json) at line '.__LINE__);	//log message
				echo "error";
			}
		}else{
			$msgStr=' No data retrieved from server at line '.__LINE__;
			$log->lwrite('Error :'.$msgStr);	//log message
			echo "error ".$msgStr;
		}
	}else{
		$msgStr='error : DB or Table name not passed at line '.__LINE__;
		$log->lwrite($msgStr);	//log message
		echo $msgStr;
	}
}else{
	$msgStr='error: Request can\'t be processed because of security reasons at line '.__LINE__;
	echo $msgStr;
	$log->lwrite($msgStr);	//log message
}
?>