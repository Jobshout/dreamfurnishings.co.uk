<?php
ini_set('display_errors',1);
require_once("include/config_inc.php");
$file_contentJson= file_get_contents("country-codes.json");
$file_contentJson = (array) json_decode($file_contentJson);
$countInserted=0;$countUpdated=0;
foreach ($file_contentJson as $Kvalue) {
	if($Kvalue->WMO!="" && $Kvalue->name!=""){
		$dataEntryArr=array();
		foreach($Kvalue as $key=>$value){
			$dataEntryArr[$key]=$value;
		}	
		if($dbResultsData = $db->countries->findOne(array("name" => $Kvalue->name))){
			$db->countries->update(array("name" => $Kvalue->name), $dataEntryArr);
			$countUpdated++;
		}else{
			$db->countries->insert($dataEntryArr);
			$countInserted++;
		}
	}
}
echo "Rows inserted : ".$countInserted."<br>Rows Updated".$countUpdated;
exit;
echo get_invoice_number(1);exit;
function syncProductsForSearch($JsonROW, $columnName='uuid', $findUUID=''){
    global $db;
   $returnParameter=false;
    
   if($existProductForSearch = $db->products_search->findOne(array($columnName => $findUUID))){
       if($db->products_search->update(array($columnName => $findUUID), $JsonROW)){
          $returnParameter=true;
        }
    } else{
        if($db->products_search->insert($JsonROW)){
           $returnParameter=true;
        }
    }
    return $returnParameter;
}
if(false){
$txtImageDirectory='images/images_data_as_txt/';
$generateImageDirectory='images/products/';
$ImageNameStr='CB8D688DE5AF4EC8A8CF6A524D4E2E58.png';
if (is_dir($txtImageDirectory)) {
    $findFile=$txtImageDirectory.$ImageNameStr.'.txt';
    if(file_exists($findFile)){
        $getFileContents=file_get_contents($findFile);
        $uncompressed = gzuncompress($getFileContents);
        $decodeImageBlob=base64_decode($uncompressed);
        $filenameStr=$generateImageDirectory.$ImageNameStr;
		$generateImageBool=file_put_contents($filenameStr, $decodeImageBlob);
        if($generateImageBool){
            echo 'Generated Image: '.$generateImageDirectory.$ImageNameStr;
        }else{
             echo 'Failed to generate: '.$ImageNameStr;
        }
    }else{
        echo 'No txt file found for the '.$ImageNameStr;
    }
}else{
    echo 'No '.$txtImageDirectory.' such directory exists!';
}
}

$dbResultsData = $db->Products->find(array('publish_on_web' => true))->sort(array("modified_timestamp" => -1))->limit(1);
foreach($dbResultsData as $OneRow){
   if(isset($OneRow['product_images']) && $OneRow['product_images']!=""){
        if(count($OneRow['product_images'])>0){
            $result=array();
						 	$count=0;
							foreach($OneRow['product_images'] as $prod_images){
								$count++;
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
                                        $txtImageDirectory='images/images_data_as_txt/';
										if (is_dir($directory)) {
											if($imageExtension!=""){
                                                 //save image as txt with gz compression
                                                $txtfilenameStr=$txtImageDirectory.$prod_images['uuid'].".txt";
                                                $compressImage = gzcompress($prod_images['encoded_image'], 9); 
												$generateImageBool=file_put_contents($txtfilenameStr, $compressImage);
                                                
                                                //generate image 
												$filenameStr=$directory.$prod_images['uuid'].".".$imageExtension;
												$generateImageBool=file_put_contents($filenameStr, $imageBlob);
												if ( $generateImageBool === false ){
													$prodImagesArr[$key]=$value;
												}else{
													$prodImagesArr["encoded_image"]="";
													$realPathStr='/images/products/'.$prod_images['uuid'].".".$imageExtension;
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
							$OneRow['product_images']=$result;
            }
      // echo json_encode($OneRow);
   }
    /**echo  syncProductsForSearch($OneRow, 'uuid', $OneRow['uuid']).'</br>';
    if($existProductForSearch = $db->products_search->findOne(array('uuid' => $OneRow['uuid']))){
        $db->products_search->update(array('uuid' => $OneRow['uuid']), $OneRow);
    } else{
        $db->products_search->insert($OneRow);
    }**/
}

exit;

//$days= strtotime("now -365 days");
$days= strtotime("now -7 days");
echo json_encode(array("temp_password" => array('$ne' => ""),"AccountDisabled"=>true, "Created" => array('$gte' =>  $days)));
exit;
ini_set('max_execution_time', 300);
ini_set('display_errors',1);
function resizeImages($src){
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
}
$scr="images/products/B2E0491010498D4E9960ACB5CB7F5598.jpg";
echo resizeImages($scr);
$inFile = "images/products/20F448E2A46FCC4594AF4E2E69A86AF6.jpg";
//using image magick
$im = new imagick(realpath($inFile).'[0]');
$im->setImageFormat("jpg");
$im->resizeImage(900,600,1,0);
//$im->writeImage ("images/products/20.jpg");
file_put_contents ("images/products/210.jpg", $im); 
//header("Content-Type: image/jpeg");
//$thumbnail = $im->getImageBlob();
//echo $thumbnail;
//end here
exit;
//using simple php
$myimage = resizeImage($inFile);
print $myimage;

function resizeImage($filename, $newwidth=900, $newheight=600){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);   
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $size=getimagesize($filename);
    switch($size["mime"]){
    	case "image/jpeg":
            $source = imagecreatefromjpeg($filename); //jpeg file
        	break;
        case "image/gif":
            $source = imagecreatefromgif($filename); //gif file
            break;
      	case "image/png":
          $source = imagecreatefrompng($filename); //png file
      		break;
    	default:
       	 $source=false;
   		 break;
    }
    
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    switch($size["mime"]){
    	case "image/jpeg":
        	imagejpeg($thumb,$filename); //save image as jpg
        	break;
        case "image/gif":
           	imagegif($thumb,$filename); //save image as jpg
      		break;
      	case "image/png":
          	imagepng($thumb,$filename); //save image as jpg
      		break;
    	default:
       	 break;
    }
    
	imagedestroy($thumb);
	return $filename;
}

exit;

require_once("include/config_inc.php");
function readImageBlob() {
    $base64 = "iVBORw0KGgoAAAANSUhEUgAAAM0AAAD
 NCAMAAAAsYgRbAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5c
 cllPAAAABJQTFRF3NSmzMewPxIG//ncJEJsldTou1jHgAAAARBJREFUeNrs2EEK
 gCAQBVDLuv+V20dENbMY831wKz4Y/VHb/5RGQ0NDQ0NDQ0NDQ0NDQ0NDQ
 0NDQ0NDQ0NDQ0NDQ0NDQ0NDQ0PzMWtyaGhoaGhoaGhoaGhoaGhoxtb0QGho
 aGhoaGhoaGhoaGhoaMbRLEvv50VTQ9OTQ5OpyZ01GpM2g0bfmDQaL7S+ofFC6x
 v3ZpxJiywakzbvd9r3RWPS9I2+MWk0+kbf0Hih9Y17U0nTHibrDDQ0NDQ0NDQ0
 NDQ0NDQ0NTXbRSL/AK72o6GhoaGhoRlL8951vwsNDQ0NDQ1NDc0WyHtDTEhD
 Q0NDQ0NTS5MdGhoaGhoaGhoaGhoaGhoaGhoaGhoaGposzSHAAErMwwQ2HwRQ
 AAAAAElFTkSuQmCC";

    $imageBlob = base64_decode($base64);

    $imagick = new Imagick();
    $imagick->readImageBlob($imageBlob);
   
    header("Content-Type: image/png");
    echo $imagick;
}
function resize_image($src , $dest , $toWidth , $toHeight , $options = array()) 
{
    if(!file_exists($src))
    {
        die("$src file does not exist");
    }
     
    //OPEN THE IMAGE INTO A RESOURCE
    $img = imagecreatefromjpeg ($src);  //try jpg
     
    if(!$img)
    {
        $img = imagecreatefromgif ($src);   //try gif
    }
     
    if(!$img)
    {
        $img = imagecreatefrompng ($src);   //try png
    }
     
    if(!$img)
    {
        die("Could Not create image resource $src");
    }
     
    //ORIGINAL DIMENTIONS
    list( $width , $height ) = getimagesize( $src );
     
    //ORIGINAL SCALE
    $xscale=$width/$toWidth;
    $yscale=$height/$toHeight;
     
    //NEW DIMENSIONS WITH SAME SCALE
    if ($yscale > $xscale) 
    {
        $new_width = round($width * (1/$yscale));
        $new_height = round($height * (1/$yscale));
    }
    else
    {
        $new_width = round($width * (1/$xscale));
        $new_height = round($height * (1/$xscale));
    }
     
    //NEW IMAGE RESOURCE
    if(!($imageResized = imagecreatetruecolor($new_width, $new_height)))
    {
        die("Could not create new image resource of width : $new_width , height : $new_height");
    }
     
    //RESIZE IMAGE
    if(! imagecopyresampled($imageResized, $img , 0 , 0 , 0 , 0 , $new_width , $new_height , $width , $height))
    {
        die('Resampling failed');
    }
     
    //STORE IMAGE INTO DESTINATION
    if(! imagejpeg($imageResized , $dest))
    {
        die("Could not save new file");
    }
     
    //Free the memory
    imagedestroy($img);
    imagedestroy($imageResized);
     
    return true;
}
$fetchProduct=$db->Products->findone(array("product_code"=>"black-white-sofa1"));
if($fetchProduct["product_images"]!=""){
	$result=array();
	foreach($fetchProduct as $key=>$value){
	
	if($key=="product_images"){
	foreach($fetchProduct["product_images"] as $prod_images){
		$prodImagesArr=array();
		foreach($prod_images as $key=>$value){
		
		if($key=="encoded_image"){
		
		
		$imageBlob = base64_decode($prod_images["encoded_image"]);
		$mimetype = getImageMimeType($imageBlob);
		if($mimetype=="jpeg"){
			$imageExtension="jpg";
		}else{
			$imageExtension=$mimetype;
		}
		$directory='images/products/';
		if (is_dir($directory)) {
			$filenameStr=$directory.$prod_images["uuid"].".".$imageExtension;
   			//file_put_contents($filenameStr, $imageBlob);
   			//$prodImagesArr["encoded_image"]="";
		}
		$realpathStr="/".$filenameStr;
		echo $realpathStr;
		try
{
    $img = new Imagick("CD30644DCF156B44957A2FFF59EFEF27.jpg");
    $img->thumbnailImage(500 , 500 , TRUE);
    $img->writeImage($realpathStr);
 
}
catch(Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "n";
   //$error++;
}   
exit;
		//echo 
		//$imagick = new Imagick($realpathStr);
		//echo $imagick->getImageSize();
	
		$imagick = new Imagick();
		$imagick->readImageBlob($imageBlob);
		echo $imagick->getSize();
		//header("Content-Type: image/$mimetype");
    	//echo $imagick;
    	break;
    	}else{
    		$prodImagesArr[$key]=$value;
    	}
    	}
    	$result['product_images'][] = $prodImagesArr;
    	//$result['product_images']=$prodImagesArr;
	}
	}else{
		$result[$key]=$value;
	}
	}
	echo json_encode($result);
}
exit;


$fetchrows=$db->categories->find();
if($fetchrows->count()>0){
	$countNum=0;
	foreach($fetchrows as $row){
		//echo json_encode($row);
		
		if($db->categories_backup->insert($row)){
			$countNum++;
		}
	}
	echo $countNum." records backedup successfully!";
}
?>