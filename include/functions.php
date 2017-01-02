<?php 
function gb_fn_linkCacheHandler($seoFriendlyUrlStr, $realUrlStr){
	if(SEOFRIENDLYFLAG){
		return $seoFriendlyUrlStr;
	}else{
		return $realUrlStr;
	}
}

function save_email_queue($recipientEmailAddr, $senderEmailAddr, $subject, $emailContent){
	global $mongoCRUDClass;
	$insert_data= array("created_timestamp" => time(), "modified_timestamp" => time(), "status" => 0, "sender_email_address" => $senderEmailAddr, "recipient_email_address" => $recipientEmailAddr, "subject" => $subject, "email_content" => $emailContent);
	$query_insert=$mongoCRUDClass->db_insert("email_queue", $insert_data);
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
				$returnMenuStr.= '<li><a href="products.htm?category='.$dbSubCategory['uuid'].'">'.ucfirst($dbSubCategory['name']);
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
	global $mongoCRUDClass;
	if($check_existingToken= $mongoCRUDClass->db_findone("Tokens", array("code" => $e))){
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

function nextID($table_name, $field_name){
	global $mongoCRUDClass;
	$get_Ids=$mongoCRUDClass->db_getMax($table_name,array($field_name=>1), $field_name);
	if($get_Ids>0){
		$ID= $get_Ids + 1;
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
                	$imageExtension="";
                	$pos = strrpos($product_images['name'], ".");
					if ($pos !== false) {
    					$imageExtension=substr($product_images['name'],intval($pos)+1) ;
    				}
                    if($totalImages==1){
                    	if($imageExtension!="" && $product_images["uuid"]!=""){
                    		$defaultImage=PRODUCT_IMAGE_DIRECTORY.$product_images["uuid"].".".$imageExtension;
                    	}else{
                            $defaultImage=$product_images["path"];
                        }
                    }else{
                        if($hasDefaultBool){
                            if($product_images["default"]=="yes"){
                            	if($imageExtension!="" && $product_images["uuid"]!=""){
                    				$defaultImage=PRODUCT_IMAGE_DIRECTORY.$product_images["uuid"].".".$imageExtension;
                    				break;
                    			}else{
                            		$defaultImage=$product_images["path"];
                            		break;
                       			}
                            }
                        }else{
                        	if($imageExtension!="" && $product_images["uuid"]!=""){
                    			$defaultImage=PRODUCT_IMAGE_DIRECTORY.$product_images["uuid"].".".$imageExtension;
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

function getBriefText($bodyStr,$returnStingLength=125){
	$firstSPosNum=stripos($bodyStr,"<p>");
	if ($firstSPosNum !== false) {
		$firstEPosNum=stripos($bodyStr,"</p>");
		if($firstEPosNum !== false) {
			$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
		}
	}
	$bodyStr=strip_tags($bodyStr);
	if(strlen($bodyStr)>$returnStingLength){
		$bodyStr=substr($bodyStr,0,$returnStingLength)."...";
	}
	
	return $bodyStr;					
}

function fetchCountryCode($nameStr){
	$countryCodeStr="";
	global $mongoCRUDClass;
	if($dbResultsData= $mongoCRUDClass->db_findone("countries", array("name" => $nameStr))){
		$countryCodeStr=$dbResultsData['ISO3166-1-numeric'];
	}
	return $countryCodeStr;
}

function image_get_mime_type($extension)
{
		// our list of mime types
        $mime_types = array(
                "pdf"=>"application/pdf"
                ,"exe"=>"application/octet-stream"
                ,"zip"=>"application/zip"
                ,"docx"=>"application/msword"
                ,"doc"=>"application/msword"
                ,"xls"=>"application/vnd.ms-excel"
                ,"ppt"=>"application/vnd.ms-powerpoint"
                ,"gif"=>"image/gif"
                ,"png"=>"image/png"
                ,"jpeg"=>"image/jpg"
                ,"jpg"=>"image/jpg"
                ,"mp3"=>"audio/mpeg"
                ,"wav"=>"audio/x-wav"
                ,"mpeg"=>"video/mpeg"
                ,"mpg"=>"video/mpeg"
                ,"mpe"=>"video/mpeg"
                ,"mov"=>"video/quicktime"
                ,"avi"=>"video/x-msvideo"
                ,"3gp"=>"video/3gpp"
                ,"css"=>"text/css"
                ,"jsc"=>"application/javascript"
                ,"js"=>"application/javascript"
                ,"php"=>"text/html"
                ,"htm"=>"text/html"
                ,"html"=>"text/html"
        );

       return $mime_types[$extension];
}



?>