<?php
require_once("include/config_inc.php");
$startLim= isset($_GET['start']) ? $_GET['start'] : 0;
$limit= isset($_GET['limit']) ? $_GET['limit'] : 9;
$category= isset($_GET['category']) ? $_GET['category'] : "";
$keyword= isset($_GET['keyword']) ? $_GET['keyword'] : "";
$breadCrumbStr="";

function find_top_categories($e){
	global $db;
	$categoryStr="";
	//$breadCrumbmenuStr="";
	$dbCategoryData = $db->categories->find(array("uuid_top_level_category" => $e), array("uuid" => 1, "name" =>1));
	
	foreach($dbCategoryData as $catData){
		$categoryFound= find_top_categories($catData["uuid"]);
		//$categoryFound=$categoryFoundStr["menu_categories"];
		//$breadCrumbmenuStr.=$categoryFoundStr["breadcrumb"];
		//$breadCrumbmenuStr.='<li class="active">'.$catData["name"].'</li>';
		
		if($categoryFound!=""){
			if($categoryStr!=""){
				$categoryStr .= ",".$categoryFound;
			}else{
				$categoryStr .= $categoryFound;
			}
		}
		if($categoryStr!=""){
			$categoryStr .= ",".$catData["uuid"];
			
		}else{
			$categoryStr .= $catData["uuid"];
		}
	}
	return $categoryStr;
	//return array('menu_categories' => $categoryStr, 'breadcrumb' => $breadCrumbmenuStr);
}

if($category!=""){
	$categoryStr="";
	if($dbfetchCatName = $db->categories->findOne(array("code" => $category), array("name" =>1, "uuid" =>1))){
		$breadCrumbStr.='<li class="active">'.$dbfetchCatName["name"].'</li>';
	
	
		$categoryStr=$dbfetchCatName["uuid"];
		$dbCategoryData = $db->categories->find(array("uuid_top_level_category" => $dbfetchCatName["uuid"]), array("uuid" => 1, "name" =>1));
		foreach($dbCategoryData as $catData){
			$categoryFound= find_top_categories($catData["uuid"]);
			//$categoryFound=$categoryFoundStr["menu_categories"];
			//$breadCrumbStr.=$categoryFoundStr["breadcrumb"];
		
			if($categoryFound!=""){
				if($categoryStr!=""){
					$categoryStr .= ",".$categoryFound;
				}else{
					$categoryStr .= $categoryFound;
				}
			}
		
			if($categoryStr!=""){
				$categoryStr .= ",".$catData["uuid"];
			}else{
				$categoryStr .= $catData["uuid"];
			}
		}
	}
	$categoryArr= explode(",",$categoryStr);
	//$categoryArr=array_unique($categoryArr);
	//echo json_encode(array('$text' => array( '$search' => $keyword ), 'product_category.uuid' => array('$in' => $categoryArr) ));
	
	if($keyword!=""){
		$dbResultsData = $db->Products->find(array('$text' => array( '$search' => $keyword ), 'product_category.uuid' => array('$in' => $categoryArr), 'publish_on_web' => true ), array('score' => array( '$meta' => "textScore"  )) )->sort( array( 'score' => array( '$meta' => "textScore" ) ) )->limit($limit)->skip($startLim);
	}else{
		$dbResultsData = $db->Products->find(array("product_category.uuid" => array('$in' => $categoryArr), 'publish_on_web' => true))->sort(array("sort_order" => -1, "modified_timestamp" => -1))->limit($limit)->skip($startLim);
	}
}else{

	$categoryArr=array();
	$dbCategoryData = $db->categories->find(array("is_active" => true), array("uuid" => 1, "name" =>1));
	foreach($dbCategoryData as $catData){
		$categoryArr[]=$catData["uuid"];
	}
	//echo json_encode(array('$text' => array( '$search' => $keyword ), 'product_category.uuid' => array('$in' => $categoryArr) ));
	if($keyword!=""){
		$dbResultsData = $db->Products->find(array('$text' => array( '$search' => $keyword ),'publish_on_web' => true, 'product_category.uuid' => array('$in' => $categoryArr) ), array('score' => array( '$meta' => "textScore"  )) )->sort( array( 'score' => array( '$meta' => "textScore" ) ) )->limit($limit)->skip($startLim);
	}else{
		$dbResultsData = $db->Products->find(array('publish_on_web' => true, "product_category.uuid" => array('$in' => $categoryArr)))->sort(array("sort_order" => -1, "modified_timestamp" => -1))->limit($limit)->skip($startLim);
	}
}

$favProductArr=array();
$cookieStr= isset($_COOKIE["DreamFurnishingVisitor"]) ? $_COOKIE["DreamFurnishingVisitor"] : 0;
if($cookieStr!=0){
    $ipAddressStr= __ipAddress();
    if($dbWishlistsData = $mongoCRUDClass->db_findone("session", array("_id" => new MongoId($cookieStr), "ip_address" => $ipAddressStr))){
        if(isset($dbWishlistsData["wishlist_products"]) && count($dbWishlistsData["wishlist_products"])>0){
            foreach($dbWishlistsData["wishlist_products"] as $row){
                $favProductArr[]=$row["uuid"];
            }
        }
    }
}

$total_records=$dbResultsData->count();

$output = array( 
	"iTotalRecords" => isset($total_records) ? $total_records : 0 ,
	"breadcrumb"=>$breadCrumbStr
);

if($dbResultsData->count()>0){
	$i=0;
	
	foreach($dbResultsData as $product){
		$row = array();
       	$row['name']=ucfirst($product["ProductName"]);
		$defaultImage=findDefaultImage($product);
 		if($defaultImage!=""){
 			$row['image']=$defaultImage;
 		}else{
			$row['image']="images/default-product-large.png";
		}
		if(isset($product["product_code"])){
			$row['code']=  $product["product_code"];
			$linkStr = gb_fn_linkCacheHandler('product-'.$product["product_code"].'.html','product.htm?code='.$product["product_code"]);
		}else{
			$row['code']=  "";
			$linkStr = gb_fn_linkCacheHandler('product.htm?uuid='.$product["uuid"],'product.htm?uuid='.$product["uuid"]);
		}
		$row['link']=  $linkStr;
		$row['id']=  $product["uuid"];
		if (in_array($product["uuid"], $favProductArr)) {
    		$row['fav']=  true;
		}else{
			$row['fav']=  false;
		}
		if(isset($product["sku"])){
			$row['sku']=  $product["sku"];
		}else{
			$row['sku']=  "";
		}
		$row['price']=  CURRENCY.$product["Unit_Price"];
		
		$i++;			
		$output['aaData'][] = $row;
	}
	$output['iTotalReturnedRecords']=$i;
}

if(count(isset($output))>0){
	echo json_encode($output);
}else{
	$output['error']="Sorry, no products found!";
	echo json_encode($output);
}

?>