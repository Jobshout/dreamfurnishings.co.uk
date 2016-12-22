<?php
require_once("include/config_inc.php");
$result=array();
$uuid= isset($_POST['uuid']) ? $_POST['uuid'] : '';
$cookie= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : '';
if($cookie!=''){
    $ipAddressStr= __ipAddress();
    $realmongoid = new MongoId($cookie);

    if($dbResultsData = $db->session->findOne(array("_id" => $realmongoid, "ip_address" => $ipAddressStr))){
        if(isset($dbResultsData["wishlist_products"]) && count($dbResultsData["wishlist_products"])>0){
            $existBool=false;
            foreach($dbResultsData['wishlist_products'] as $key=>$value)  {   
                if($value==$uuid){
                    $existBool=true;
                    break;
                }
            }

            //check product exists or not
            if($existBool) {
                $result['success']= "This product already exists in your wishlist!";
            }else{
                $set_v= array("wishlist_products" => $uuid);
                if($db->session->update(array("_id" => $realmongoid), array('$push' => $set_v))){
                    $result["success"]="Added this product successfully to your wish list!";
                }else{
                    $result["error"]="Please try after sometime!";
                }
            }
        }else{
            $set_v= array("wishlist_products" => $uuid);
            if($db->session->update(array("_id" => $realmongoid), array('$push' => $set_v))){
                $result["success"]="Added this product successfully to your wishlist!";
            }else{
                $result["error"]="Please try after sometime!";
            }
        }
    }else{
        $session_values= array("ip_address" => $ipAddressStr , "wishlist_products" => array($uuid));
        //echo json_encode($session_values);
        if($db->session->insert($session_values)){
            $result["success"]="Added this product successfully to your wishlist!";
            setcookie("DreamFurnishingVisitor", $session_values['_id'], time()+60*60*24*365);
        }else{
            $result["error"]="Please try after sometime!";
        }
    }
}else{
    $session_values= array("ip_address" => $ipAddressStr , "wishlist_products" => array($uuid));
    //echo json_encode($session_values);
    if($db->session->insert($session_values)){
        $result["success"]="Added this product successfully to your wishlist!";
        setcookie("DreamFurnishingVisitor", $session_values['_id'], time()+60*60*24*365);
    }else{
        $result["error"]="Please try after sometime!";
    }
}
echo json_encode($result);
?>