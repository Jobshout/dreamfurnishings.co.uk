<?php
ini_set('max_execution_time', 900);
ini_set('memory_limit', '1024M');
//echo ini_get('memory_limit');exit;
ini_set('display_errors',1);
define("SAVEIMAGESONDISK", true);
date_default_timezone_set("Europe/London");
include_once("../include/mongo_connection.php");
include_once("logging.php");

$images_root_disk_path="/var/www/html/vhosts/dreamfurnishings.co.uk/public_ftp/";

$log = new Logging();
$log->lfile('logs/log_'.date("j-n-Y").'.txt');

// write message to the log file
$log->lwrite('------------------------------------------------------');		//log message

$tablename=isset($_REQUEST['tablename']) ? $_REQUEST['tablename'] : "Products";
$dbname=isset($_REQUEST['dbname']) ? $_REQUEST['dbname'] : "DreamFurnishings";
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
		
		$row=json_decode($data);

		if(count($row)>0){
			$log->lwrite('Decoded the json successfully at line '.__LINE__); //log message
			
			//foreach($arr_tbl_data as $row){
				$updatecol='uuid';
				if(isset($row->uuid_product) && $row->uuid_product!=""){
					$log->lwrite("Record with [".$tablename."]".$updatecol." : ".$row->uuid_product);	//log message
					$productImageUUID=$row->uuid;
                   
					echo "Record with ".$updatecol." : ".$row->uuid_product." has been "; 
					
					if($productFound = $collection->findOne(array($updatecol => $row->uuid_product))){
						$prodImagesArr=array();
						if(SAVEIMAGESONDISK){
                        			$prod_images=$row;
                                    
                                    $realPathStr="";
                                    foreach($prod_images as $key=>$value){
                                        if($key=="encoded_image"){
                                            $imageBlob = base64_decode($prod_images->encoded_image);

                                            $pos = strpos($prod_images->name, ".");
                                            if ($pos !== false) {
                                                $imageExtension=substr($prod_images->name,intval($pos)+1) ;
                                            }

                                           // $directory='../images/products/';
                                           // $txtImageDirectory='../images/images_data_as_txt/';
                                           
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
                                                      /*  chmod($realPathStr, 0777);  // octal; correct value of mode                                                                                                      \
                                                        chown($realPathStr, "nginx");                                                                                                                                                                                     
                                                        chgrp($realPathStr, "nginx");    */                                                                                                                                                                                      
                                                        $log->lwrite("Generated product image from blob at disk path: ".$realPathStr."at line ".__LINE__); //log message
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
                           
                    	}
                    	
						 if(isset($productFound['product_images']) && count($productFound['product_images'])>0){
            				$existBool=false;
            				$get_image_Details='';
            				foreach($productFound['product_images'] as $imageDetails)  {
               					if($imageDetails['uuid']==$productImageUUID){
									$existBool=true;
                    				$get_image_Details=$imageDetails;
                    				break;
                				}
            				}

            				//check product exists or not
           					if($existBool) {
           						$del_item= $collection->update(array($updatecol => $row->uuid_product), array('$pull'=> array( "product_images"=> $get_image_Details)));
								if($del_item){
									// insert d same row							
									$set_v= array('product_images' => $prodImagesArr);
                					if($collection->update(array($updatecol => $row->uuid_product), array('$push' => $set_v))){
                   						$log->lwrite('Success: Updated image for product, at line '.__LINE__);	//log message
										echo "updated at line ".__LINE__;
                					}else{
                    					$log->lwrite('Error: No such product found in database, failed at line '.__LINE__);	//log message
										echo "updation fail at line ".__LINE__;
                					}
								}
            				}else{
                				$set_v= array('product_images' => $prodImagesArr);
                				if($collection->update(array($updatecol => $row->uuid_product), array('$push' => $set_v))){
                   					$log->lwrite('Success: Updated image for product, at line '.__LINE__);	//log message
									echo "updated at line ".__LINE__;
                				}else{
                    				$log->lwrite('Error: No such product found in database, failed at line '.__LINE__);	//log message
									echo "updation fail at line ".__LINE__;
                				}
           					}
        				}else{
            				$set_v= array('product_images' => $prodImagesArr);
            				if($collection->update(array($updatecol => $row->uuid_product), array('$push' => $set_v))){
               					$log->lwrite('Success: Updated image for product, at line '.__LINE__);	//log message
								echo "updated at line ".__LINE__;
            				}else{
                				$log->lwrite('Error: No such product found in database, failed at line '.__LINE__);	//log message
								echo "updation fail at line ".__LINE__;
            				}
        				}
						
						
					}	else{
						$log->lwrite('Error: No such product found in database, failed at line '.__LINE__);	//log message
						echo "updation fail at line ".__LINE__;
					}
				}else{
					$log->lwrite('Error: No search parameters are sent, failed at line '.__LINE__);	//log message
					echo "error at line ".__LINE__;
				}
 				echo ": ".date('d/m/Y H:i:s')."<br/>";
			//}
		}else{
			$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
			echo "error at line ".__LINE__;
		}
	}else{
		$log->lwrite('Error: No data retrieved from server at line '.__LINE__);	//log message
		echo "error at line ".__LINE__;
	}
}else{
	$log->lwrite('Error: DB or Table name not passed at line '.__LINE__);	//log message
	echo "error at line ".__LINE__;
}
?>