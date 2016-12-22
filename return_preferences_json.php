<?php
require_once("include/config_inc.php");
$totalNum=0;
$action= isset($_GET['action']) ? $_GET['action'] : '';

$objectName="wishlist_products";
if($action=="wishlist"){
	$objectName="wishlist_products";
}else if($action=="cart"){
	$objectName="cart";
}

$favProductArr=array();
$cookieStr= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
if($cookieStr!=''){
    $ipAddressStr= __ipAddress();
    $wishlistItemsArr=array();
    if($dbWishlistsData = $db->session->findOne(array("_id" => new MongoId($cookieStr), "ip_address" => $ipAddressStr))){
        if(isset($dbWishlistsData[$objectName]) && count($dbWishlistsData[$objectName])>0){
        	$wishlistItemsArr=$dbWishlistsData[$objectName];
            foreach($dbWishlistsData[$objectName] as $wishlistProds){
                $favProductArr[]=$wishlistProds["uuid"];
            }
        }
    }
    //echo json_encode(array('publish_on_web' => true, "product_category.uuid" => array('$in' => $favProductArr)));
    $dbResultsData = $db->Products->find(array('publish_on_web' => true, "uuid" => array('$in' => $favProductArr)))->sort(array("modified_timestamp" => -1));

    $output = array();

    if($dbResultsData->count()>0){
    	$totalNum=$dbResultsData->count();

        foreach($dbResultsData as $product){
            $row = array();
            foreach($wishlistItemsArr as $subObject){
            	if($product["uuid"]==$subObject["uuid"]){
            		$availableOptionsStr="";
            		foreach($subObject as $key=>$value){
            			if($key!="uuid" && $key!="Quantity" && $key!="UnitPrice"){
            				if($availableOptionsStr!=""){
                				$availableOptionsStr.= ", ".$key."= ".$value;
                			}else{
                				$availableOptionsStr.= $key."= ".$value;
                			}
                		}
                		if($key=="Quantity"){
                			$row[$key]= $value;
                		}
                	}
                	$row['options']=$availableOptionsStr;
                	break;
                }
            }
            $row['name']=ucfirst($product["ProductName"]);
            $defaultImage=findDefaultImage($product);
            
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
            $row['currency']=  CURRENCY;
            $row['price']=  $product["Unit_Price"];
            $output['aaData'][] = $row;
        }
    }else{
        $output['error']="Sorry, no products found in ".$action."!";
    }
}else{
    $output['error']="Sorry, no products found in ".$action."!";
}
$output['iTotalRecords']=$totalNum;
echo json_encode($output);
?>