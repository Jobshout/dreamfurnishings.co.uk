<?php
ini_set('max_execution_time', 300);
include_once("config.php");

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){

	$log->lfile('logs/log_'.date("j-n-Y").'.txt');

	// write message to the log file
	$log->lwrite('------------------------------------------------------');		//log message

	$tablename=isset($_GET['tablename']) ? $_GET['tablename'] : "";
	if($tablename!="" && $dbname!=""){
		$file_path='php://input';
		$collection = $mon_db->$tablename;	$log->lwrite('Established connection with '.$dbname.' Database at line '.__LINE__); //log message
	
		$data= file_get_contents($file_path);
	
		if($data!=""){
			$data = gzuncompress($data);
			header("Content-type: application/json");
			$log->lwrite('Retrieved compressed data from server at line '.__LINE__); //log message
		
			$arr_tbl_data=json_decode($data);

			if(count($arr_tbl_data)>0){
				$log->lwrite('Decoded the json successfully at line '.__LINE__); //log message
			
				foreach($arr_tbl_data as $row){
					if(isset($_GET['updatecol']) && $_GET['updatecol']!=""){
						$updatecol=$_GET['updatecol'];
						$log->lwrite("Record with [".$tablename."]".$updatecol." : ".$row->$updatecol);	//log message
						echo "Record with ".$updatecol." : ".$row->$updatecol." has been ";

						$sameRowExist = $collection->find(array($updatecol => $row->$updatecol));
						$num_exist = $sameRowExist->count();
						if($num_exist>0){
							//if($collection->update(array($updatecol => $row->$updatecol), array('$set' => $row))){
							// the following will replace the existing json
							if($collection->update(array($updatecol => $row->$updatecol), $row)){
								$log->lwrite('Data updated successfully at line '.__LINE__); //log message
								echo "updated";
							}else{
								$log->lwrite('Error: Data updation failed at line '.__LINE__); //log message
								echo "updation failed";
							}
						}	else{
							if($collection->insert($row)){
								$log->lwrite('Data inserted successfully at line '.__LINE__); //log message
								echo "inserted";
							}else{
								$log->lwrite('Error: Data insertion failed at line '.__LINE__); //log message
								echo "insertion fail";
							}
						}
					}else{
						try {
							$collection->insert($row);
							$log->lwrite('Data inserted successfully at line '.__LINE__); //log message	
							echo "inserted";
						}catch(MongoCursorException $e) {
							$log->lwrite('Error: Data insertion failed at line '.__LINE__); //log message
							echo "insertion fail";
						}
					}
 				echo ": ".date('d/m/Y H:i:s')."<br/>";
				}
			}else{
				$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
				echo 'error : No data retrieved from server at line '.__LINE__;
			}
		}else{
			$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
			echo "error : No data retrieved from server at line ".__LINE__;
		}
	}else{
		$log->lwrite('Error: DB or Table name not passed at line '.__LINE__);	//log message
		echo "error : DB or Table name not passed at line ".__LINE__;
	}
}else{
	$msgStr=' Request can\'t be processed because of security reasons at line '.__LINE__;
	echo 'error:'.$msgStr;
	$log->lwrite('Error:'.$msgStr);	//log message
}
?>