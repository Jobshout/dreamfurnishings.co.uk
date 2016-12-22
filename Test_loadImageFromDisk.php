<?php
require_once("include/config_inc.php");

$loadImageSrcCode=1; //0 means load from local disk, 1 means load from S3 bucket

$awsBucketNameStr="dreamfurnishings";
$vt_service="s3";

$vt_host="https://".$awsBucketNameStr.".".$vt_service.".amazonaws.com";

$passedImageUUIDStr= isset($_GET['uuid']) ? $_GET['uuid'] : '';
$fileExtensionStr=".jpg";
$displayImageSrc="";

if($loadImageSrcCode==1){
	$txtImageDirectory=$vt_host."/images/";
	$passedImageUUIDStr="FED162733C2D9D44A45DDC1F4970C193";
	echo $txtImageDirectory;
}else{
	$txtImageDirectory='images/images_data_as_txt/';
}

if ($txtImageDirectory!="") {
	if($passedImageUUIDStr!=""){
     		$findTxtFile=$txtImageDirectory.$passedImageUUIDStr.'.txt';
           
            //if(file_exists($findTxtFile)){
                $getFileContents=file_get_contents($findTxtFile);
                //echo "<br><br><br>Image file content (".strlen($getFileContents).") : " . $getFileContents;
                
                $uncompressed = gzuncompress($getFileContents);
                //echo "<br><br><br>Image file content after gzuncompress (".strlen($uncompressed)."): " . $uncompressed;

                $decodeImageBlob=base64_decode($uncompressed);
                 //echo "<br><br><br>Image file content after base64_decode (".strlen($decodeImageBlob)."): " . $decodeImageBlob;
               
                $mimetype = getImageMimeType($decodeImageBlob);
				$displayImageSrc="data:image/".$mimetype.";base64,".$uncompressed;
                
            //}else{
            //    echo 'No txt file found for the '.$passedImageUUIDStr;
            //}
    }
}

?>
<img src="<?php echo $displayImageSrc; ?>">