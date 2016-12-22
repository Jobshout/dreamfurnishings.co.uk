<?php
require_once("include/config_inc.php");

$favProductArr=array();
$cookieStr= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
if($cookieStr!=''){
    $ipAddressStr= __ipAddress();
    if($dbWishlistsData = $db->session->findOne(array("_id" => new MongoId($cookieStr), "ip_address" => $ipAddressStr))){
        if(isset($dbWishlistsData["wishlist_products"]) && count($dbWishlistsData["wishlist_products"])>0){
            foreach($dbWishlistsData["wishlist_products"] as $key=>$value){
                $favProductArr[]=$value;
            }
        }
    }
    //echo json_encode(array('publish_on_web' => true, "product_category.uuid" => array('$in' => $favProductArr)));
    $dbResultsData = $db->Products->find(array('publish_on_web' => true, "uuid" => array('$in' => $favProductArr)))->sort(array("modified_timestamp" => -1));

    $output = array();

    if($dbResultsData->count()>0){
        foreach($dbResultsData as $product){
            $row = array();
            $row['name']=ucfirst($product["ProductName"]);
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
            if($defaultImage!=""){
                $row['image']=$defaultImage;
            }else{
                $row['image']="images/default-product-large.png";
            }
            if(isset($product["product_code"])){
                $row['code']=  $product["product_code"];
            }else{
                $row['code']=  "";
            }
            $row['id']=  $product["uuid"];

            if(isset($product["sku"])){
                $row['sku']=  $product["sku"];
            }else{
                $row['sku']=  "";
            }
            $row['price']=  CURRENCY.$product["Unit_Price"];
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
    }else{
        $output['error']="Sorry, no products found in wishlist!";
        echo json_encode($output);
    }
}else{
    $output['error']="Sorry, no products found in wishlist!";
	echo json_encode($output);
}
?>