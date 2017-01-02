if ( SAVE_IMAGES_IN_MONGO )
{

$prod_images=$row;
                                    foreach($prod_images as $key=>$value){
                                        if($key=="encoded_image"){

                                            /*
echo "image_data_as_txt: " . $prod_images->encoded_image . "<br>";
echo "uuid: " . $prod_images->uuid . "<br>";
*/
$collectionNameStr="images_data";
$collectionObj = $mon_db->$collectionNameStr;

					$exist = $collectionObj->find(array($updatecol => $prod_images->uuid));
					$num_exist = $exist->count();
					if($num_exist>0){

							if($collectionObj->update(array($updatecol => $prod_images->uuid), array("image_data" => $prod_images->encoded_image))){
								$log->lwrite('Image data updated successfully at line '.__LINE__); //log message
								echo "updated";
							}else{
								$log->lwrite('Error: Image data updation failed at line '.__LINE__); //log message
								echo "updation failed";
							}


} else {

$collectionObj->insert(array("image_data" => $prod_images->encoded_image, $updatecol => $prod_images->uuid));  

}
                                        }
                                    }



}