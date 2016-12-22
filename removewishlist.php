<?php
require_once("include/config_inc.php");

$uuid= isset($_POST['uuid']) ? $_POST['uuid'] : 0;
$cookie= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';

if($cookie!=''){
    $ipAddressStr= __ipAddress();
    $realmongoid = new MongoId($cookie);
    $result=array();
    if($dbResultsData = $db->session->findOne(array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
        $set_v= array("wishlist_products" => $uuid);
        if($db->session->update(array("_id" => $realmongoid), array('$pull' => $set_v))){
            $result["success"]="Deleted this product successfully from your wish list!";
        }else{
            $result["error"]="Please try after sometime!";
        }
    }else{
        $result["error"]="No such product found in wishlist!";
    }
}else{
    $result["error"]="You have no products in your wishlist!";
}
echo json_encode($result);
?>