<?php
$menuHTMLStr="";
$productCatHtmlStr="";
if($dbCategories = $db->categories->find(array("is_active" => true, "uuid_top_level_category" => ""))->sort(array("sort_order" => -1, "name" => 1))){
	foreach($dbCategories as $dbCategory){
		$catUUIDStr=$dbCategory['uuid'];
		$catCodeStr=$dbCategory['code'];
		$displayCategoryBool=false;
		$dbProductsForCat = $db->Products->find(array( 'publish_on_web' => true, "product_category.uuid" => $catUUIDStr));
		if($dbProductsForCat->count()>0){
			$displayCategoryBool=true;
		}
				
		$subMenu = find_sub_categories($catUUIDStr,$displayCategoryBool,1);
		$subMenuStr= $subMenu["menu_categories"];
		$p_subMenuStr= $subMenu["product_categories"];
		$displayCategoryBool= $subMenu["displayBool"];
		
		if($displayCategoryBool){
			$menuHTMLStr .= '<li>';
			$categoryLinkStr=gb_fn_linkCacheHandler('products-by-category-'.$dbCategory['code'].'.html','products.htm?category='.$dbCategory['code']);
			if($subMenuStr!="" && $subMenuStr!='<ul class="dropdown-menu"></ul>'){
				$menuHTMLStr .=  '<a href="'.$categoryLinkStr.'">'.ucfirst($dbCategory['name']).'<span class="caret"></span></a>';
				$menuHTMLStr .=  $subMenuStr;
			}else{
				$menuHTMLStr .=  '<a href="'.$categoryLinkStr.'">'.ucfirst($dbCategory['name']).'</a>';
			}
			$menuHTMLStr .=  '</li>';
		
			$productCatHtmlStr .= '<li> <a href="javascript:void(0)" onClick="fetch_cat_products(\''.$catCodeStr.'\')" aria-expanded="false">'.ucfirst($dbCategory['name']);
			if($p_subMenuStr!="" && $p_subMenuStr!='<ul aria-expanded="false" class="collapse"></ul>'){
				$productCatHtmlStr .=  '<span class="glyphicon arrow"></span></a>';
				$productCatHtmlStr .=  $p_subMenuStr;
			}else{
				$productCatHtmlStr .=  '</a>';
			}
			$productCatHtmlStr .=  '</li>';
		}
	}
}
?>
<style>
@media(max-width:992px){
	.navbar-nav{
	margin-right: -15px !important;
	margin-top:10px;
	}
}
.navbar-nav{
	 margin-right: 50px;
}
.cart_items{
font-size: 13px;
background-color: #fff;
padding: 0px 6px;
-webkit-border-radius: 9px;
-moz-border-radius: 9px;
border-radius: 12px;
margin-left: 5px;
color: #000;
}
</style>
<div STYLE="background-color:#e0e3df;">
	<div CLASS="row" STYLE="padding: 7px 20px; width:100%;">
		<div CLASS="col-md-12 text-right" STYLE="padding-right:0px;">
			<?php if(isset($isUserSignedInBool) && $isUserSignedInBool==true && isset($userLoggedIn)){	?>
				<a HREF="myaccount.htm" STYLE="color:#000; font-size: 13px;" >Hi, <?php echo $userLoggedIn['First name']." ".$userLoggedIn["Surname"];	?></a> | <a HREF="logout.htm" STYLE="color:#000; font-size: 13px;" > <i CLASS="fa fa-sign-in" ></i> Logout</a>
			<?php } else	{	?>
				<a HREF="login.htm" STYLE="color:#000; font-size: 13px;" > <i CLASS="fa fa-sign-in" ></i> Sign In</a> | <a HREF="register.htm" STYLE="color:#000; font-size: 13px;" > <i CLASS="fa fa-edit"></i> Register</a>
			<?php } ?>
			<!-- |
			<a HREF="#" STYLE="color:#000; font-size: 13px;" > <i CLASS="fa fa-sign-out"></i> Sign Out</a>-->
			<a HREF="cart.htm?<?php echo rand();?>" CLASS="btn btn-danger btn-sm displayCartsClass" STYLE="margin-left:7px; display:none;"> <i CLASS="fa fa-cart-plus"></i> &nbsp; Cart <span class="cart_items cartItemsClass">0</span></a>
		</div>
	</div>
</div>
<header>
	<div class="navbar navbar-default" role="navigation" style="margin-bottom:0px;">
        <div class="navbar-header">
          	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            	<span class="sr-only">Toggle navigation</span>
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
            	<span class="icon-bar"></span>
         	</button>
         	<a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home"><img src="images/logo.jpg" class="logo-st img-responsive" ></a>
        </div>
        <div class="navbar-collapse collapse main-nav">
        	<div STYLE="float:right;" CLASS="hidden-sm hidden-xs">
		  		<form id="mainSearch" class="searchnew" action="products.htm" method="post">
              		<div id="label"><label for="search-terms" id="search-label">search</label></div>
               	 	<div id="input"><input type="text" name="keyword" id="search-terms" placeholder="Enter search Products..."></div>
           		</form>
			</div>
			<div class="visible-sm visible-xs" style="margin-top:20px;">
				<form id="mainSearch" role="search" action="products.htm" method="post">
					<input class="form-control" placeholder="Search" name="keyword" id="search-terms" style="width:100%; border:none; height:38px; margin:0px;" type="text">
  				</form>  
  			</div>
  			<!--<div CLASS="visible-sm visible-xs" STYLE="margin-top:20px;">
 				<form id="search" role="search" action="products.htm" method="post">
   					<input class="form-control" placeholder="Search" name="keyword" id="search-terms" type="text" STYLE="width:100%; border:none; height:38px; margin:0px;">
   				</form>
  			</div>-->
  			
			<!-- Right nav -->
         	<ul class="nav navbar-nav navbar-right ">
            	<li><a id="ProductMainMenu" href="javascript:void(0)" title="Products">Products<span class="caret"></span></a>
            		<ul class="dropdown-menu">
            			<?php if(isset($menuHTMLStr) && $menuHTMLStr!=""){  ?>
         				<?php echo $menuHTMLStr; ?>
         				<?php } ?>
         				<li><a href="products.htm" title="View all products">View All Products</a></li>
      				</ul>
            	</li>
            	<li><a href="<?php echo gb_fn_linkCacheHandler('about-us.html','content.htm?code=about-us');?>" title="Company Profile">Company Profile</a></li>
            	<li><a href="<?php echo gb_fn_linkCacheHandler('blogs.htm','blogs.htm'); ?>" title="Blog">Blog </a></li>
            	<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" href="javascript:void(0)" title="Home">Home</a></li>
				<li><a href="<?php echo gb_fn_linkCacheHandler('contact.htm','contact.htm'); ?>" title="Contact">Contact</a></li>
				<li id="wishlistMenu" style="display:none;"><a href="<?php echo gb_fn_linkCacheHandler('wishlist.htm','wishlist.htm');?>" title="Wishlist"><i class="glyphicon glyphicon-heart"></i></a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</header>