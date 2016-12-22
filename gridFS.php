<?php
require_once("include/config_inc.php");
if(isset($_POST["submit"])) {
    if($_FILES['fileToUpload']['size'] > 0){
		$fileName = $_FILES['fileToUpload']['name'];
		$tmpName  = $_FILES['fileToUpload']['tmp_name'];
		$fileSize = $_FILES['fileToUpload']['size'];
		$fileType = $_FILES['fileToUpload']['type'];
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		$content = base64_encode($content);
		fclose($fp);
		if(!get_magic_quotes_gpc()){
			$fileName = addslashes($fileName);
		}
		$insert_data= array("fileName" => $fileName, "fileSize" => $fileSize, "fileType" => $fileType, "content" => $content);
		$query_insert = $db->test_collection->insert($insert_data);
		
		//$grid = $db->getGridFS();
		// store
		//$storedfile = $grid->storeUpload('fileToUpload',$fileName); //load file into MongoDB  
		
		//echo $storedfile;
	}
}
?>
<!DOCTYPE html>
<html>
<body>
<?php $images=$db->test_collection->find();
foreach($images as $image){
	?>
	<img src="data:image/jpeg;base64,<?php echo $image['content']; ?>" />
<?php	}

?>

<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html> 