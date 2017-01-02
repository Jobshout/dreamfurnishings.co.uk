<?php
ini_set('max_execution_time', 900);
ini_set('memory_limit', '1024M');
define("SAVE_IMAGES_ON_DISK", false);
include_once("config.php");

if(isset($_GET['token']) && $_GET['token']!="" && secure_authentication($_GET['token'])){
$images_root_disk_path="../../public_ftp/";

$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$tablename=isset($_REQUEST['tablename']) ? $_REQUEST['tablename'] : "Products";
if($tablename!="" && $dbname!=""){
	$file_path='php://input';
	
	$collection = $mon_db->$tablename;
	$log->lwrite('Established connection with mongodb at line '.__LINE__); //log message
	
	$data= file_get_contents($file_path);
	
	if($data!=""){
		$data = gzuncompress($data);
		header("Content-type: application/json");
		$log->lwrite('Retrieved compressed data from server at line '.__LINE__); //log message
		
		$arr_tbl_data=json_decode($data);

		if(count($arr_tbl_data)>0){
			$log->lwrite('Decoded the json successfully at line '.__LINE__); //log message
			
			foreach($arr_tbl_data as $row){
				if(isset($_REQUEST['updatecol']) && $_REQUEST['updatecol']!=""){
					$updatecol=$_REQUEST['updatecol'];
					$log->lwrite("Record with [".$tablename."]".$updatecol." : ".$row->$updatecol);	//log message
                    if( SAVE_IMAGES_ON_DISK ){
                        if(isset($row->product_images) && $row->product_images!=""){

                            if(count($row->product_images)>0){

                                $log->lwrite("Product contain ".count($row->product_images)." images at line ".__LINE__); //log message
                                $result=array();
                                $count=0;
                                foreach($row->product_images as $prod_images){
                                    $count++;
                                    $prodImagesArr=array();
                                    $realPathStr="";
                                    foreach($prod_images as $key=>$value){
                                        if($key=="encoded_image"){
                                            $imageBlob = base64_decode($prod_images->encoded_image);

                                            $pos = strpos($prod_images->name, ".");
                                            if ($pos !== false) {
                                                $imageExtension=substr($prod_images->name,intval($pos)+1) ;
                                            }

                                            $directory=$images_root_disk_path.'images/products/';
                                            $txtImageDirectory= $images_root_disk_path.'images/products/';
                                            if (is_dir($directory)) {
                                                if($imageExtension!=""){
                                                    //save image as txt with gz compression
                                                    if (is_dir($txtImageDirectory)) {
                                                        //$txtfilenameStr=$txtImageDirectory.$prod_images->uuid.".".$imageExtension.".txt";
                                                        $txtfilenameStr=$txtImageDirectory.$prod_images->uuid.".txt";
                                                        $compressImage = gzcompress($prod_images->encoded_image, 9); 
                                                        $generateImageTxtBool=file_put_contents($txtfilenameStr, $compressImage);
                                                    }

                                                    //generate image 
                                                    $filenameStr=$directory.$prod_images->uuid.".".$imageExtension;
                                                    $generateImageBool=file_put_contents($filenameStr, $imageBlob);
                                                    if ( $generateImageBool === false ){
                                                        $prodImagesArr[$key]=$value;
                                                    }else{
                                                        $prodImagesArr["encoded_image"]="";
                                                        $realPathStr='/images/products/'.$prod_images->uuid.".".$imageExtension;
                                                        $prodImagesArr["path"]=$realPathStr;
                                                        $log->lwrite("Generated ".$count." product image from blob at disk path: ".$realPathStr."at line ".__LINE__); //log message
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
                    }

					echo "Record with ".$updatecol." : ".$row->$updatecol." has been "; 
					
					$exist = $collection->find(array($updatecol => $row->$updatecol));
					$num_exist = $exist->count();
					if($num_exist>0){
						try {
							if($collection->update(array($updatecol => $row->$updatecol), $row)){
								$log->lwrite('Data updated successfully at line '.__LINE__); //log message
								echo "updated";
							}else{
								$log->lwrite('Error: Data updation failed at line '.__LINE__); //log message
								echo "updation failed";
							}
						}catch(MongoCursorException $e) {
							$log->lwrite('Error: Data updation failed at line '.__LINE__); //log message
							echo "updation failed";
						}
					}	else{
						try {
							if($collection->insert($row)){
								$log->lwrite('Data inserted successfully at line '.__LINE__); //log message
								echo "inserted";
							}else{
								$log->lwrite('Error: Data innsertion failed at line '.__LINE__); //log message
								echo "insertion fail";
							}
						}catch(MongoCursorException $e) {
							echo "insertion fail";
							$log->lwrite('Error: Data insertion failed at line '.__LINE__);	//log message
						}
					}
				}else{
					try {
						$collection->insert($row);
						$log->lwrite('Data inserted successfully at line '.__LINE__);	//log message
						echo "inserted";
					}catch(MongoCursorException $e) {
						$log->lwrite('Error: Insertion failed at line '.__LINE__);	//log message
						echo "insertion fail";
					}
				}
 				
 				
 				if(isset($row->product_images_uuids) && count($row->product_images_uuids)>0){
 					
				  $log->lwrite('IN product_images_uuids, at line '.__LINE__); //log message                                                               

					$newProductImagesArr=$row->product_images_uuids;
 					if($productFound = $collection->findOne(array($updatecol => $row->$updatecol))){
 						if(isset($productFound['product_images']) && count($productFound['product_images'])>0){
 							$prodImagesArr=array();
 							$deleteImagesTxt="";
 							foreach($productFound['product_images'] as $imageDetails)  {
               					if (in_array($imageDetails['uuid'], $newProductImagesArr)){
  									$prodImagesArr[] = $imageDetails;
  								}else{
  									$deleteImagesTxt=$deleteImagesTxt.", ".$imageDetails['uuid'];
  								}
            				}
            				
            				$set_v= array('product_images' => $prodImagesArr);
                			if($collection->update(array($updatecol => $row->$updatecol), array('$push' => $set_v))){
                   				$log->lwrite('Success: product_images updated for product, at line '.__LINE__);	//log message
								echo "product_images updated, deleted images are : ".$deleteImagesTxt." at line ".__LINE__;
								$log->lwrite("product_images updated, deleted images are : ".$deleteImagesTxt." at line ".__LINE__);
                			}else{
                    			$log->lwrite('Error: No such product found in database, failed at line '.__LINE__);	//log message
								echo "Error: No such product found in database, failed at line ".__LINE__;
                			}
 						}
 					}
 				}

 				
 				echo ": ".date('d/m/Y H:i:s')."<br/>";
			}
		}else{
			$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
			echo "error";
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
	$msgStr='Request can\'t be processed because of security reasons at line '.__LINE__;
	echo 'error : '.$msgStr;
	$log->lwrite('Error : '.$msgStr);	//log message
}
?>