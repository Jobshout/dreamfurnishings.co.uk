<?php
ini_set('display_errors',1);
//include_once("config.inc.php");
include_once("../include/mongo_connection.php");
include_once("logging.php");

$log = new Logging();
$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$tablename=isset($_GET['tablename']) ? $_GET['tablename'] : "DreamFurnishings";
$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "";
if($tablename!="" && $dbname!=""){
	$file_path='php://input';
	$mon_db= $conn->$dbname;
	$collection = $mon_db->$tablename;		$log->lwrite('Established connection with '.$dbname.' Database at line '.__LINE__); //log message
	
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
		$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
		echo "error";
	}
}else{
	$log->lwrite('Error: DB or Table name not passed at line '.__LINE__);	//log message
	echo "error";
}
?>