<?php 
ini_set('max_execution_time', 300);
ini_set('display_errors',1);
require_once("include/config_inc.php");
$startLim= isset($_GET['start']) ? $_GET['start'] : 0;
$limit= isset($_GET['limit']) ? $_GET['limit'] : 10;
function resizeImages($src){
	try {
		$im = new imagick(realpath($src).'[0]');
		$im->setImageFormat("jpg");
		$im->resizeImage(900,600,1,0);
		//$im->writeImage ("images/products/20.jpg");
		$translate_feed=file_put_contents ($src, $im); 
		if ( $translate_feed === false ){
			return false;
		}else{
			return true;
		}
	} catch (ImagickException $e) {
		return false;
	}
}
$fetchProducts=$db->Products->find()->sort(array("modified_timestamp" => -1))->limit($limit)->skip($startLim);
if(count($fetchProducts)>0){
	foreach($fetchProducts as $d_prod){
		//echo $d_prod["product_code"]."<br>";
		if(isset($d_prod["product_images"]) && count($d_prod["product_images"])>0){
			$result=array();
			foreach($d_prod["product_images"] as $prod_images){
				$prodImagesArr=array();
				$realPathStr="";
				foreach($prod_images as $key=>$value){
					if($key=="encoded_image"){
						$imageBlob = base64_decode($prod_images['encoded_image']);
						$pos = strpos($prod_images['name'], ".");
						if ($pos !== false) {
    						$imageExtension=substr($prod_images['name'],intval($pos)+1) ;
    					}
						
						$directory='images/products/';
						if (is_dir($directory)) {
							$filenameStr=$directory.$prod_images['uuid'].".".$imageExtension;
							//echo $filenameStr."==========";exit;
							$generateImageBool=file_put_contents($filenameStr, $imageBlob);
							/**if ( $generateImageBool === false ){
								
							}else{
								resizeImages($filenameStr);
							}
   								**/		
   							$prodImagesArr["encoded_image"]=$prod_images['encoded_image'];
   							$realPathStr='images/products/'.$prod_images['uuid'].".".$imageExtension;
   							if ( $generateImageBool === false ){
   							
   							}else{
   								$prodImagesArr["path"]=$realPathStr;
   							}
   						}
					}elseif($key=="path"){
						$prodImagesArr["path"]=$realPathStr;
					}else{
						$prodImagesArr[$key]=$value;
					}
				}
				$result[] = $prodImagesArr;
			}
			if(count($result)>0){
				$updateProductImgs=$db->Products->update(array("uuid" => $d_prod["uuid"]), array('$set' => array("product_images"=>$result)));
				if($updateProductImgs){
					//echo json_encode($result)."<br>";
					echo $d_prod["product_code"]."<br>";
				}
			}
		}
	}
}
?>