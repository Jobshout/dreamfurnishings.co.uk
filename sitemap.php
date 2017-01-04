<?php 
require_once("include/config_inc.php");
require_once("include/main_header.php");

function fetch_sub_categories($e,$displayBool=false,$level=1){
	global $db;
	$returnMenuStr="";
	$displayCategorywithProductsBool=$displayBool;
	
	$level=$level+1;
	$dbSubCategories = $db->categories->find(array("is_active" => true, "uuid_top_level_category" => $e))->sort(array("sort_order" => -1, "name" => 1));
	if($dbSubCategories->count()>0){
		foreach($dbSubCategories as $dbSubCategory){
			$catUUIDStr=$dbSubCategory['uuid'];
			$displayCategoryBool=false;
			$dbProductsForCat = $db->Products->find(array('publish_on_web' => true, "product_category.uuid" => $catUUIDStr));
			if($dbProductsForCat->count()>0){
				$displayCategoryBool=true;
			}
			
			$subMenu = fetch_sub_categories($dbSubCategory['uuid'],$displayCategoryBool,$level);
			$subMenuStr= $subMenu["sub_categories"];
			$displayCategoryBool= $subMenu["displayBool"];
			
			if($displayCategoryBool){
				$displayCategorywithProductsBool=$displayCategoryBool;
				$returnMenuStr.= '<h4 class="site-map-sub-cat-hding">'.$dbSubCategory['name'].'</h4>';
				$returnMenuStr.= '<div class="clearfix">';
                if($dbProductsForCat->count()>0){
                    $returnMenuStr.= '<ul class="site-map-list-2';
                    //if($subMenuStr!="" && $subMenuStr!=''){
                    	$returnMenuStr.= ' sub-cat ';
                    //}
                    $returnMenuStr.= ' ">';
                    foreach($dbProductsForCat as $productDetails){
                        $returnMenuStr.= '<li>'.$productDetails["ProductName"].'</li>';
                   	}
					$returnMenuStr.= '</ul>';
				}
                $returnMenuStr.= '</div>';
				if($subMenuStr!="" && $subMenuStr!=''){
					$returnMenuStr .=  $subMenuStr;
				}
			
			}
		}
		
	}
	
	return array('sub_categories' => $returnMenuStr, 'displayBool' => $displayCategorywithProductsBool);     
}

$latestProducts = $db->Products->find(array('publish_on_web' => true, 'product_category' => array('$ne' => "")))->sort(array("sort_order" => -1,"modified_timestamp" => -1));
$webPages = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)), 'code' => array('$ne' => "")))->sort(array("posted_timestamp" => -1));

?>
<body>
<?php require_once("include/header.php"); ?>
<section>
		<div class="headingbcg " >
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-sm-8">
            <h1>
              Dream Furnishings Sitemap
            </h1>
          </div>
          <div class="col-md-4 col-sm-4 ">
		  <div class="text-right bred-crumb-xs clearfix">
            <ol class="breadcrumb ">
             		<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm'); ?>" title="Home">Home</a></li>
					<li class="active">Sitemap</li>
            </ol>
			</div>
          </div>
        </div>
      </div>
    </div>
		<div CLASS="container">
				<div CLASS="row ">
						<div class="col-sm-12" >
              <h2 class="site-map-cat-hding">Pages</h2>
		        	
                    <div class="clearfix">
                    	<ul class="site-map-list">
                            <li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm'); ?>"  title="Home">Home</a></li>
                            <li><a href="<?php echo gb_fn_linkCacheHandler('products.htm','products.htm'); ?>"  title="Products">Products</a>
                            <li><a href="<?php echo gb_fn_linkCacheHandler('about-us.html','content.htm?code=about-us');?>"  title="Company Profile">Company Profile</a></li>
            				<li><a href="<?php echo gb_fn_linkCacheHandler('blogs.htm','blogs.htm');?>"  title="Blog">Blog </a></li>
            				<li><a href="<?php echo gb_fn_linkCacheHandler('news.htm','news.htm');?>"  title="News">News </a></li>
							<li><a href="<?php echo gb_fn_linkCacheHandler('contact.htm','contact.htm');?>"  title="Contact">Contact</a></li>
   					   </ul>
   					 </div>
   					<?php if($fetchCategories = $db->categories->find(array("is_active" => true, "uuid_top_level_category" => ""))->sort(array("sort_order" => -1, "name" => 1))){ 
   						if($fetchCategories->count()>0){
   						?>
   						<h2 class="site-map-cat-hding">Products</h2>
   						<?php
   							foreach($fetchCategories as $dbCategory){
   								$catUUIDStr=$dbCategory['uuid'];
								$displayCategoryBool=false;
								$dbProductsForCat = $db->Products->find(array( 'publish_on_web' => true, "product_category.uuid" => $catUUIDStr));
								if($dbProductsForCat->count()>0){
									$displayCategoryBool=true;
								}
								$subMenu = fetch_sub_categories($catUUIDStr,$displayCategoryBool,1);
								$subMenuStr= $subMenu["sub_categories"];
								$displayCategoryBool= $subMenu["displayBool"];
								
								if($displayCategoryBool){
							?>
   							<!--<h4 class="site-map-cat-hding"><a href="products.php?category=<?php echo $dbCategory['uuid'];?>"><?php echo $dbCategory['name'];?></a></h4>-->
                    		<h4 class="site-map-cat-hding"><?php echo ucfirst($dbCategory['name']);?></h4>
                    		<!--<h4 class="site-map-sub-cat-hding">Italian Bed</h4>-->
                           	<div class="clearfix">
                           		<?php if($dbProductsForCat->count()>0){	?>
                       	 		<ul class="site-map-list-2 <?php if(isset($subMenuStr) && $subMenuStr!=""){ echo 'sub-cat' ; } ?>">
                       	 		<?php foreach($dbProductsForCat as $productDetails){	?>
                          			<li><a  href="<?php echo gb_fn_linkCacheHandler('product-'.$productDetails["product_code"].'.html','product.htm?code='.$productDetails["product_code"]);?>"><?php echo $productDetails["ProductName"];?></a></li>
                          			<?php }	?>
								</ul>
								<?php } ?>
                        	</div>
                	<?php		if(isset($subMenuStr) && $subMenuStr!=""){
									echo $subMenuStr;                	
                				}
                				}	
                			}
                		}
                	} ?>
                	<?php if($webPages->count()>0){	
                	$countBlogs=0; $blogsHTmlStr="";
					foreach($webPages as $webPage){
						if($webPage['type']=="blog"){ 
							$countBlogs++;
							$urlStr=gb_fn_linkCacheHandler($webPage['code'].'.html','content.htm?code='.$webPage['code']);
							$blogsHTmlStr.='<li><a href="'.$urlStr.'">'.$webPage['title'].'</a></li>';
						}
					}
					if($countBlogs>0 && $blogsHTmlStr!=""){
                	?>
                   		<h2 class="site-map-cat-hding">Blog</h2>
                    	<div class="clearfix">
                   			<ul class="site-map-list-2">
                         		<?php echo $blogsHTmlStr; ?>
                        	</ul>
                		</div>
                	<?php } 
                	}	?>
                	
                    <?php if($webPages->count()>0){	
                	$countNews=0; $newsHTmlStr="";
					foreach($webPages as $webPage){
						if($webPage['type']=="news"){ 
							$countNews++;
							$urlStr=gb_fn_linkCacheHandler($webPage['code'].'.html','content.htm?code='.$webPage['code']);
							$newsHTmlStr.='<li><a href="'.$urlStr.'">'.$webPage['title'].'</a></li>';
						}
					}
					if($countNews>0 && $newsHTmlStr!=""){
                	?>
                   		<h2 class="site-map-cat-hding">News</h2>
                    	<div class="clearfix">
                   			<ul class="site-map-list-2">
                         		<?php echo $newsHTmlStr; ?>
                        	</ul>
                		</div>
                	<?php } 
                	}	?>
				 </div>
             </div>
				
		</div>
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
</body>
</html>
</html>
