<?php 
require_once("include/config_inc.php");
require_once("include/main_header.php");
$keyword= isset($_POST['keyword']) ? $_POST['keyword'] : "";
$category= isset($_GET['category']) ? $_GET['category'] : "";
$limit=6;
$page=isset($_GET['page']) ? intval($_GET['page']) : 0;
if($page==0 || $page==1){
	$startLimNum=0;
}else{
	$startLimNum=($page-1)*6;
}

function find_top_categories($e){
	global $db;
	$categoryStr="";
	$dbCategoryData = $db->categories->find(array("uuid_top_level_category" => $e), array("uuid" => 1, "name" =>1));
	
	foreach($dbCategoryData as $catData){
		$categoryFound= find_top_categories($catData["uuid"]);
		
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
}

if($category!=""){
	$categoryStr="";
	if($dbfetchCatName = $db->categories->findOne(array("code" => $category), array("name" =>1, "uuid" =>1))){
		$categoryStr=$dbfetchCatName["uuid"];
		$dbCategoryData = $db->categories->find(array("uuid_top_level_category" => $dbfetchCatName["uuid"]), array("uuid" => 1, "name" =>1));
		foreach($dbCategoryData as $catData){
			$categoryFound= find_top_categories($catData["uuid"]);
			
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
	
	if($keyword!=""){
		$dbResultsData = $db->Products->find(array('$text' => array( '$search' => $keyword ), 'product_category.uuid' => array('$in' => $categoryArr), 'publish_on_web' => true ), array('score' => array( '$meta' => "textScore"  )) )->sort( array( 'score' => array( '$meta' => "textScore" ) ) )->limit($limit)->skip($startLimNum);
	}else{
		$dbResultsData = $db->Products->find(array("product_category.uuid" => array('$in' => $categoryArr), 'publish_on_web' => true))->sort(array("sort_order" => -1, "modified_timestamp" => -1))->limit($limit)->skip($startLimNum);
	}
}else{

	$categoryArr=array();
	$dbCategoryData = $db->categories->find(array("is_active" => true), array("uuid" => 1, "name" =>1));
	foreach($dbCategoryData as $catData){
		$categoryArr[]=$catData["uuid"];
	}
	if($keyword!=""){
		$dbResultsData = $db->Products->find(array('$text' => array( '$search' => $keyword ),'publish_on_web' => true, 'product_category.uuid' => array('$in' => $categoryArr) ), array('score' => array( '$meta' => "textScore"  )) )->sort( array( 'score' => array( '$meta' => "textScore" ) ) )->limit($limit)->skip($startLimNum);
	}else{
		$dbResultsData = $db->Products->find(array('publish_on_web' => true, "product_category.uuid" => array('$in' => $categoryArr)))->sort(array("sort_order" => -1, "modified_timestamp" => -1))->limit($limit)->skip($startLimNum);
	}
}
$total_pages= $dbResultsData->count();
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
?>
</head>
<body>
<?php require_once("include/header.php"); ?>
<style>
.pricetext{
	font-weight:normal!important;
	font-size: 14px!important;
font-family: arial!important;
color:#515151;
}

</style>
	<section>
		<div class="headingbcg " >
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-sm-8">
            <h1>
              Products
            </h1>
          </div>
          <div class="col-md-4 col-sm-4 ">
		  <div class="text-right bred-crumb-xs clearfix">
            <ol class="breadcrumb">
              	<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
				<li class="active">Products</li>
            </ol>
			</div>
          </div>
        </div>
      </div>
    </div>
<div class="container">
	<div class="row clearfix">
		<?php if(isset($productCatHtmlStr) && $productCatHtmlStr!=""){  ?>
    	<div class="col-md-3 column hidden-xs hidden-sm">
      		<aside class="sidebar">
      			<nav class="sidebar-nav">
					<?php if(isset($productCatHtmlStr) && $productCatHtmlStr!=""){  ?>
					<ul class="metismenu" id="menu">
						<li STYLE="background-color: #333; padding: 4px 2px;" ><a href="products.htm" title="View All Products">View All Products</a></li>
         				<?php echo $productCatHtmlStr; ?>
					</ul>
      				<?php } ?>
      			</nav>
			</aside>		
	   	</div>
	   	<div class="col-md-9 column">
	   	<?php }else{ ?>
		<div class="col-md-12 column">
		<?php } ?>
			
      		<div class="" id="products_section">
				<?php if(isset($dbResultsData) && $dbResultsData->count()>0){	
					foreach($dbResultsData as $product){
						$defaultImage=findDefaultImage($product);
						$linkStr=gb_fn_linkCacheHandler('prdouct.htm?uuid='.$product["uuid"],'prdouct.htm?uuid='.$product["uuid"]);
						if(isset($product["product_code"])){
							$linkStr=gb_fn_linkCacheHandler('prdouct-'.$product["product_code"].'.html','prdouct.htm?code='.$product["product_code"]);
						}
 					?>
					<div class="col-sm-6 col-md-6 col-lg-4 column">
						<div class="productbox">
							<div style="background:#fff;">
								<a href="product-evolution.html" title="<?php echo ucfirst($product["ProductName"]);	?>">
									<img src="<?php echo $defaultImage;?>" class="img-responsive prdt-listing-pg-img" alt="<?php echo ucfirst($product["ProductName"]);	?>" onerror="this.src='images/default-product-large.png'">
								</a>
							</div>
							<div class="producttitle">
								<a href="product-evolution.html" title="<?php echo ucfirst($product["ProductName"]);	?>"><?php echo ucfirst($product["ProductName"]);	?></a>
							</div>
							<div class="pull-right">
								<a href="product-evolution.html" title="View full details" role="button">
									<span class="glyphicon glyphicon-info-sign" style="font-size:16px;"></span>
								</a>
								
								<a href="javascript:void(0)" onclick="alterWishlist('<?php echo $product["uuid"]; ?>')" title="Wishlist" class="wclass_<?php echo $product["uuid"]; ?> <?php if (in_array($product["uuid"], $favProductArr)) {	echo 'whishlist_sel'; }	?>"><i class="glyphicon glyphicon-heart"></i></a>
							</div>
							<div class="pricetext"><strong>Code: </strong><?php echo $product["sku"];?></div>
						</div>
					</div>
				<?php 	}
					}else	{	?>
					<div class="alert alert-danger" role="alert">Sorry, no products found!</div>
				<?php }	?>
      		</div>
      		<?php require_once("include/pagination.php");
			echo $pagination;
			?>
			<!--<nav aria-label="Page navigation example">
  				<ul class="pagination">
    				<li class="page-item">
      					<a class="page-link" href="#" aria-label="Previous">
        					<span aria-hidden="true">&laquo;</span>
        					<span class="sr-only">Previous</span>
      					</a>
    				</li>
    				<li class="page-item"><a class="page-link" href="#">1</a></li>
   					<li class="page-item">
      					<a class="page-link" href="#" aria-label="Next">
       					<span aria-hidden="true">&raquo;</span>
        				<span class="sr-only">Next</span>
      					</a>
    				</li>
  				</ul>
			</nav>-->
    	</div>
  	</div>
</div> 
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script src="js/tree-view.js"></script>
<script>
var nPageSize=6, xhr,category="<?php echo isset($_GET['category']) ? $_GET['category'] : ""; ?>", keyword="<?php echo isset($keyword) ? $keyword : ""; ?>";
var start=0, totalNum=0 , end=nPageSize;

function fetch_cat_products(e){
	window.location.href="prods.php?category="+e;
}
function movePage(counter){
	var linkStr="prods.php?page="+counter;
	if(category!=""){
		linkStr+="&category="+category;
	}
	window.location.href=linkStr;
}
function alterWishlist(e){
	var actionStr="wishlist";
	if($(".wclass_"+e).hasClass("whishlist_sel")){
		$.ajax({
			url: 'removeUserPreferences.htm',
			type: 'POST',
			data: {"uuid" : e, "action" : actionStr },
			dataType: 'json',
			cache: false,
			success: function(response){
				if(response.success){
					$(".wclass_"+e).removeClass("whishlist_sel");
					fetchUserPreferences(actionStr);
				}				
			}
		});
	}else{
		$.ajax({
			url: 'addUserPreferences.htm',
			type: 'POST',
			data: {"uuid" : e, "action" : actionStr },
			dataType: 'json',
			cache: false,
			success: function(response){
				if(response.success){
					$(".wclass_"+e).addClass("whishlist_sel");
					fetchUserPreferences(actionStr);
				}				
			}
		});
	}
}
$(function () {
	$('#menu').metisMenu();
});
</script>
</body>
</html>