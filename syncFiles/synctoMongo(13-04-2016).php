<?php
ini_set('max_execution_time', 300);
ini_set('display_errors',1);
$conn = new Mongo( 'mongodb://192.168.1.21:27017/' );

$tablename=isset($_GET['tablename']) ? $_GET['tablename'] : "";
$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "";
if($tablename!="" && $dbname!=""){
	$file_path='php://input';
	$mon_db= $conn->$dbname;
	$collection = $mon_db->$tablename;
	$data= file_get_contents($file_path);
	if($data!=""){
	$data = gzuncompress($data);
	header("Content-type: application/json");
	$arr_tbl_data=json_decode($data);

	if(count($arr_tbl_data)>0){
		foreach($arr_tbl_data as $row){
			if(isset($_GET['updatecol']) && $_GET['updatecol']!=""){
				$updatecol=$_GET['updatecol'];
				echo "Record with ".$updatecol." : ".$row->$updatecol." has been ";

				$exist = $collection->find(array($updatecol => $row->$updatecol));
				$num_exist = $exist->count();
				if($num_exist>0){
					if($collection->update(array($updatecol => $row->$updatecol), array('$set' => $row))){
						echo "updated";
					}else{
						echo "updation failed";
					}
				}	else{
					if($collection->insert($row)){
						echo "inserted";
					}else{
						echo "insertion fail";
					}
				}
			}else{
				$collection->insert($row);
				echo "inserted";
			}
 		echo ": ".date('d/m/Y H:i:s')."<br/>";
		}
	}else{
		echo "error";
	}
	}else{
		echo "error";
	}
}else{
	echo "error";
}
?>