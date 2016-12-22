<?php
require_once("include/config_inc.php");
$result=array();
$cookie= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
if($cookie!=''){
    $ipAddressStr= __ipAddress();

    $realmongoid = new MongoId($cookie);
   
    if($dbResultsData = $db->session->findOne(array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
        if(isset($dbResultsData["wishlist_products"]) && count($dbResultsData["wishlist_products"])>0){
            $result["success"]= count($dbResultsData["wishlist_products"]);
        }else{
            $result["error"]="No such product found in wishlist!";
        }	
    }else{
        $result["error"]="No such product found in wishlist!";
    }
}else{
    $result["error"]="No products found in wishlist!"; 
}
echo json_encode($result);
?>