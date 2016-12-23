<?php 
function gb_fn_linkCacheHandler($seoFriendlyUrlStr, $realUrlStr){
	if(SEOFRIENDLYFLAG){
		return $seoFriendlyUrlStr;
	}else{
		return $realUrlStr;
	}
}

function save_email_queue($recipientEmailAddr, $senderEmailAddr, $subject, $emailContent){
	global $db;
	$insert_data= array("created_timestamp" => time(), "modified_timestamp" => time(), "status" => 0, "sender_email_address" => $senderEmailAddr, "recipient_email_adddress" => $recipientEmailAddr, "subject" => $subject, "email_content" => $emailContent);
	$query_insert = $db->email_queue->insert($insert_data);
	if($query_insert){
		return true;
	}else{
		return false;
	}
}

function find_sub_categories($e,$displayBool=false,$level=1){
	global $db;
	$returnMenuStr="";
	$returnProductsStr="";
	$displayCategorywithProductsBool=$displayBool;
	
	$level=$level+1;
	$dbSubCategories = $db->categories->find(array("is_active" => true, "uuid_top_level_category" => $e))->sort(array("name" => 1));
	if($dbSubCategories->count()>0){
		$returnMenuStr.= '<ul class="dropdown-menu">';
		$returnProductsStr.= '<ul aria-expanded="false" class="collapse">';
		
		foreach($dbSubCategories as $dbSubCategory){
			$catUUIDStr=$dbSubCategory['uuid'];
			$displayCategoryBool=false;
			$dbProductsForCat = $db->Products->find(array('publish_on_web' => true, "product_category.uuid" => $catUUIDStr));
			if($dbProductsForCat->count()>0){
				$displayCategoryBool=true;
			}
			
			$subMenu = find_sub_categories($dbSubCategory['uuid'],$displayCategoryBool,$level);
			$subMenuStr= $subMenu["menu_categories"];
			$subProdStr= $subMenu["product_categories"];
			$displayCategoryBool= $subMenu["displayBool"];
			
			if($displayCategoryBool){
				$displayCategorywithProductsBool=$displayCategoryBool;
				$returnMenuStr.= '<li><a href="products.php?category='.$dbSubCategory['uuid'].'">'.ucfirst($dbSubCategory['name']);
				$returnProductsStr.= '<li><a href="javascript:void(0)" onClick="fetch_cat_products(\''.$catUUIDStr.'\')">'.ucfirst($dbSubCategory['name']);
				
				if($subMenuStr!="" && $subMenuStr!='<ul class="dropdown-menu"></ul>'){
					$returnMenuStr .=  '<span class="caret"></span></a>';
					
					$returnMenuStr .=  $subMenuStr;
				}else{
					$returnMenuStr .=  '</a>';
				}
				$returnMenuStr .=  '</li>';
			
				if($subProdStr!="" && $subProdStr!='<ul aria-expanded="false" class="collapse"></ul>'){
					if($level==1){
						$returnProductsStr .=  '<span class="caret"></span></a>';
					}else{
						$returnProductsStr .=  '<span class="fa plus-times"></span></a>';
					}
					$returnProductsStr .=  $subProdStr;
				}else{
					$returnProductsStr .=  '</a>';
				}
			
				$returnProductsStr .=  '</li>';
			}
		}
		
		$returnMenuStr.= '</ul>';
		$returnProductsStr.= '</ul>';
	}
	
	return array('menu_categories' => $returnMenuStr, 'product_categories' => $returnProductsStr, 'displayBool' => $displayCategorywithProductsBool);     
}

function NewGuid() { 
	$s = strtoupper(md5(uniqid(rand(),true))); 
	$guidText = 
		substr($s,0,8) . '-' . 
		substr($s,8,4) . '-' . 
		substr($s,12,4). '-' . 
		substr($s,16,4). '-' . 
		substr($s,20); 
	return $guidText;
}
function sortBy($field, &$array, $direction = 'asc')
{
	global $db;
    usort($array, create_function('$a, $b', '
        $a = $a["' . $field . '"];
        $b = $b["' . $field . '"];

        if ($a == $b)
        {
            return 0;
        }

        return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
    '));

    return true;
}

function __ipAddress(){
	//Just get the headers if we can or else use the SERVER global
	if ( function_exists( 'apache_request_headers' ) ) {
	$headers = apache_request_headers();
	} else {
	$headers = $_SERVER;
	}

	$pPT_ipAddrStr	= $_SERVER["REMOTE_ADDR"];
	$clientIPStr='';

	if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$clientIPStr = $headers['X-Forwarded-For'];
	}elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )) {
		$clientIPStr = $headers['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$arr_ip=explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);
		$clientIPStr = $arr_ip[0];
	}else if(isset($_SERVER["HTTP_X_REAL_IP"])){
		$clientIPStr = $_SERVER["HTTP_X_REAL_IP"];
	}else if(isset($_SERVER['HTTP_CLIENT_IP'])){
		$clientIPStr = $_SERVER['HTTP_CLIENT_IP'];
	}else if(isset($_SERVER["HTTP_X_FORWARDED"])){
		$clientIPStr = $_SERVER["HTTP_X_FORWARDED"];
	}else if(isset($_SERVER["HTTP_FORWARDED_FOR"])){
		$clientIPStr = $_SERVER["HTTP_FORWARDED_FOR"];
	}else if(isset($_SERVER["HTTP_FORWARDED"])){
		$clientIPStr = $_SERVER["HTTP_FORWARDED"];
	}
	if( $clientIPStr != "") { $pPT_ipAddrStr=$clientIPStr; }

	
	$mail_ip_str='';
	if($pPT_ipAddrStr!=''){
		$mail_ip_str.=$pPT_ipAddrStr;
	}
	return $mail_ip_str;
}

function get_token_value($e){
	$token_content="";
	global $db;
	//if($check_existingToken= $db->Tokens->findOne(array("code" => $code, "Status" => 1))){
	if($check_existingToken= $db->Tokens->findOne(array("code" => $e))){
		$token_content=$check_existingToken["contentTxt"];
	}
	return $token_content;
}

function getBytesFromHexString($hexdata){
  for($count = 0; $count < strlen($hexdata); $count+=2)
    $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

  return implode($bytes);
}

function getImageMimeType($imagedata){
  $imagemimetypes = array( 
    "jpeg" => "FFD8", 
    "png" => "89504E470D0A1A0A", 
    "gif" => "474946",
    "bmp" => "424D", 
    "tiff" => "4949",
    "tiff" => "4D4D"
  );

  foreach ($imagemimetypes as $mime => $hexbytes){
    $bytes = getBytesFromHexString($hexbytes);
    if (substr($imagedata, 0, strlen($bytes)) == $bytes)
      return $mime;
  }

  return NULL;
}

function validChr($str) {
//$result = preg_match('/^[ A-Za-z0-9_\'\-!@:=#?\$&+",.\(\)\/]+$/',$str); //characters used before
	$result = preg_match('/^[ \r\nA-Za-z0-9_\'\-!`~%@*:=#?\$&+",.\(\)\/]+$/',$str); //due to XSS only alpha numeric, _,',-,!,`,~,%,@,*,:,=,#,?,$,&,+,",.,(,),/
	return $result;
}

function characterMessage($str) {
    return "Please only use alpha-numeric characters in the ".$str;
}

function nextID($table_name){
	global $db;
	$get_Ids=$db->$table_name->find()->sort(array("ID" => -1))->limit(1);
	if($get_Ids->count() >0){
		foreach($get_Ids as $get_Id){
			$ID= $get_Id['ID'] + 1;
		}
	}else{
		$ID= 1;
	}
	return $ID;
}

function get_invoice_number($trans_id){
	global $db;
	
	$inv_prefix= "DF";
	$inv_string='0000000';
	$length1=strlen($inv_string);
	$length2=strlen($trans_id);
	$inv_number=$inv_prefix . substr($inv_string,0,$length1-$length2) . $trans_id;
	return $inv_number;
}

function findDefaultImage($product){
	$defaultImage="";
	
			if(isset($product['product_images']) && count($product['product_images'])>0){ 
                $totalImages= count($product['product_images']);
                $hasDefaultBool=false;
                if($totalImages>1){
                    foreach($product['product_images'] as $product_images){
                        if($product_images["default"]=="yes"){
                            $hasDefaultBool=true;
                        }
                    }
                }
                foreach($product['product_images'] as $product_images){
                    if($totalImages==1){
                        if(isset($product_images['path']) && $product_images['path']!="" && file_exists($product_images['path'])===true){ 
                            $defaultImage=$product_images["path"];
                        }elseif(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
                            $defaultBase64=$product_images["encoded_image"];
                            $imgdata = base64_decode($defaultBase64);
                            $mimetype = getImageMimeType($imgdata);
                            $defaultImage="data:image/".$mimetype.";base64,".$defaultBase64;
                        }else{
                            $defaultImage=$product_images["path"];
                        }
                    }else{
                        if($hasDefaultBool){
                            if($product_images["default"]=="yes"){
                                if(isset($product_images['path']) && $product_images['path']!="" && file_exists($product_images['path'])===true){ 
                                    $defaultImage=$product_images["path"];
                                }elseif(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
                                    $defaultBase64=$product_images["encoded_image"];
                                    $imgdata = base64_decode($defaultBase64);
                                    $mimetype = getImageMimeType($imgdata);
                                    $defaultImage="data:image/".$mimetype.";base64,".$defaultBase64;
                                    break;
                                }else{
                                    $defaultImage=$product_images["path"];
                                    break;
                                }
                            }
                        }else{
                            if(isset($product_images['path']) && $product_images['path']!="" && file_exists($product_images['path'])===true){ 
                                $defaultImage=$product_images["path"];
                            }elseif(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
                                $defaultBase64=$product_images["encoded_image"];
                                $imgdata = base64_decode($defaultBase64);
                                $mimetype = getImageMimeType($imgdata);
                                $defaultImage="data:image/".$mimetype.";base64,".$defaultBase64;
                                break;
                            }else{
                                $defaultImage=$product_images["path"];
                                break;
                            }
                        }
                    }
                }
            }
        return $defaultImage;
}

function getBriefText($bodyStr){
	$firstSPosNum=stripos($bodyStr,"<p>");
	if ($firstSPosNum !== false) {
		$firstEPosNum=stripos($bodyStr,"</p>");
		$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
	}
	$bodyStr=strip_tags($bodyStr);
	if(strlen($bodyStr)>125){
		$bodyStr=substr($bodyStr,0,125)."...";
	}
	return $bodyStr;					
}

function fetchCountryCode($nameStr){
	global $db;
	$countryCodeStr="";
	if($dbResultsData = $db->countries->findOne(array("name" => $nameStr))){
		$countryCodeStr=$dbResultsData['ISO3166-1-numeric'];
	}
	return $countryCodeStr;
}
?>