<?php
ini_set('max_execution_time', 900);
ini_set('memory_limit', '1024M');
//echo ini_get('memory_limit');exit;
ini_set('display_errors',1);
define("SAVEIMAGESONDISK", true);
date_default_timezone_set("Europe/London");
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
				if(isset($_GET['updatecol']) && $_GET['updatecol']!=""){
					$updatecol=$_GET['updatecol'];
					$log->lwrite("Record with [".$tablename."]".$updatecol." : ".$row->$updatecol);	//log message
                    if(SAVEIMAGESONDISK){
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

                                            $directory='../images/products/';
                                            $txtImageDirectory='../images/images_data_as_txt/';
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
 				echo ": ".date('d/m/Y H:i:s')."<br/>";
			}
		}else{
			$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
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