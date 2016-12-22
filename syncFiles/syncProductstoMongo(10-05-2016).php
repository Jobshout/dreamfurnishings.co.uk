<?php
ini_set('max_execution_time', 300);
ini_set('memory_limit', '1024M');
//echo ini_get('memory_limit');exit;
ini_set('display_errors',1);
$conn = new Mongo( 'mongodb://192.168.1.21:27017/' );

$tablename=isset($_GET['tablename']) ? $_GET['tablename'] : "";
$dbname=isset($_GET['dbname']) ? $_GET['dbname'] : "";
if($tablename!="" && $dbname!=""){
	//header("Content-type: application/x-www-form-urlencoded");
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
					
					if(isset($row->product_images) && $row->product_images!=""){
						if(count($row->product_images)>0){
						 	$result=array();
							foreach($row->product_images as $prod_images){
								$prodImagesArr=array();
								$realPathStr="";
								foreach($prod_images as $key=>$value){
									if($key=="encoded_image"){
										$imageBlob = base64_decode($prod_images->encoded_image);
										
										$pos = strpos($prod_images->name, ".");
										if ($pos !== false) {
    										$imageExtension=substr($prod_images->name,intval($pos)+1) ;
    									}
    					
										$directory='../images/products/';
										if (is_dir($directory)) {
											if($imageExtension!=""){
												$filenameStr=$directory.$prod_images->uuid.".".$imageExtension;
												$generateImageBool=file_put_contents($filenameStr, $imageBlob);
												if ( $generateImageBool === false ){
													$prodImagesArr[$key]=$value;
												}else{
													$prodImagesArr["encoded_image"]="";
													$realPathStr='/images/products/'.$prod_images->uuid.".".$imageExtension;
   													$prodImagesArr["path"]=$realPathStr;
												}	
											}else{
												$prodImagesArr[$key]=$value;
											}
   										}
									}elseif($key=="path"){
										if($realPathStr!=""){
											$prodImagesArr["path"]=$realPathStr;
										}else{
											$prodImagesArr[$key]=$value;
										}
									}else{
										$prodImagesArr[$key]=$value;
									}
									
								}
								$result[] = $prodImagesArr;
							}
							$row->product_images=$result;
						}
					}
					
					$updatecol=$_GET['updatecol'];
					echo "Record with ".$updatecol." : ".$row->$updatecol." has been ";

					$exist = $collection->find(array($updatecol => $row->$updatecol));
					$num_exist = $exist->count();
					if($num_exist>0){
						try {
							if($collection->update(array($updatecol => $row->$updatecol), $row)){
								echo "updated";
							}else{
								echo "updation failed";
							}
						}catch(MongoCursorException $e) {
							echo "updation fail";
						}
					}	else{
						try {
							if($collection->insert($row)){
								echo "inserted";
							}else{
								echo "insertion fail";
							}
						}catch(MongoCursorException $e) {
							echo "insertion fail";
						}
					}
				}else{
					try {
						$collection->insert($row);
						echo "inserted";
					}catch(MongoCursorException $e) {
						echo "insertion fail";
					}
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