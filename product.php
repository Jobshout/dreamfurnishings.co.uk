<?php 
require_once("include/config_inc.php");
$defaultImage=""; $referrerBreadCrumbStr=""; $defaultImageUUIDStr="";
$referrerBreadCrumbLinkStr="";
if(!isset($code)) { $code = isset($_GET['code']) ? $_GET['code'] : ''; $code = str_replace(".html", "", $code); }
if(!isset($uuidStr)) { $uuidStr = isset($_GET['uuid']) ? $_GET['uuid'] : ''; }

if(isset($code) && $code!=''){
	$dbProductData = $mongoCRUDClass->db_findone("Products", array("product_code" => $code));
	//$dbProductData = $db->Products->findOne(array("product_code" => $code));
	if(count($dbProductData)>0){
	}else{
		header("location:404.htm");
		exit;
	}
}elseif(isset($uuidStr) && $uuidStr!=''){
	$dbProductData = $mongoCRUDClass->db_findone("Products", array("uuid" => $uuidStr));
	if(count($dbProductData)>0){
	
	}else{
		header("location:404.htm");
		exit;
	}
}else{
	header("location:404.htm");
	exit;
}

if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ 
	$pWindowTitleTxt = $dbProductData['ProductName'];
}
//$pMetaKeywordsTxt = $documentdetail['meta_tag_keywords'];
if(isset($dbProductData['Description']) && $dbProductData['Description']!=""){ 
    $QuickbodyStr= $dbProductData["Description"];
	$firstSPosNum=stripos($QuickbodyStr,"<p>");
	if ($firstSPosNum !== false) {
		$firstEPosNum=stripos($QuickbodyStr,"</p>");
		$QuickbodyStr=substr($QuickbodyStr,$firstSPosNum,$firstEPosNum);
	}
	$QuickbodyStr=strip_tags($QuickbodyStr);
	if(strlen($QuickbodyStr)>125){
		$QuickbodyStr=substr($QuickbodyStr,0,125)."...";
	}
	$pMetaDescriptionTxt = $QuickbodyStr;
}
if(isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=""){
	$findpro  = 'products.htm';
	$posIn = strpos($_SERVER["HTTP_REFERER"], $findpro);
	if ($posIn !== false) {
   		$referrerBreadCrumbLinkStr=$findpro;
   		$referrerBreadCrumbStr="Products";
	}
	$findwish   = 'wishlist.htm';
	$posWish = strpos($_SERVER["HTTP_REFERER"], $findwish);
	if ($posWish !== false) {
		$referrerBreadCrumbStr="Wishlist";
   		$referrerBreadCrumbLinkStr=$findwish;
	}
}
$unitPriceOfProduct=0;
if(isset($dbProductData['Unit_Price']) && $dbProductData['Unit_Price']>0){
	$unitPriceOfProduct=$dbProductData['Unit_Price'];
}
require_once("include/main_header.php"); ?>
<link rel="stylesheet" href="css/imagezoom/imagezoom.css" />
<link rel="stylesheet" href="css/elastislide/es-cus.css" />
<style>
.add-to-cart-btn{
	width:100%;
	padding: 12px 15px;
	margin-bottom: 10px;
}
			 
.buy-now-btn {
	padding: 13px 15px;
   	width: 100%;
}

.pading-lft{
	padding-left:0px;
}

@media (max-width: 768px) {
	.pading-lft{
	padding-left:15px;
}
	
}
			 
			 </style>
</head>
<body>
<?php require_once("include/header.php"); ?>
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo ucfirst($dbProductData['ProductName']);	}?></h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<?php if(isset($referrerBreadCrumbStr) && $referrerBreadCrumbStr!="" && isset($referrerBreadCrumbLinkStr) && $referrerBreadCrumbLinkStr!=""){	?>
							<li><a href="<?php echo gb_fn_linkCacheHandler($referrerBreadCrumbLinkStr, $referrerBreadCrumbLinkStr); ?>" title="<?php echo $referrerBreadCrumbStr; ?>"><?php echo $referrerBreadCrumbStr; ?></a></li>
							<?php } ?>
							<li class="active"><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo ucfirst($dbProductData['ProductName']);	}?></li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
    </div>
</section>

<div class="container">
	<div class="row">
		<input value="<?php echo $unitPriceOfProduct; ?>" id="unitPriceOfProduct" name="unitPriceOfProduct" type="hidden">
     	<div class="col-md-12  productboxmain">
        	<div class="row clearfix">
        	<?php if(isset($dbProductData['product_images']) && $dbProductData['product_images']>0){  ?>
          		<div class="col-md-8 "> 
 					 <!--demo2-->
 					<?php if(isset($dbProductData['product_images']) && count($dbProductData['product_images'])>0){ 
 						$carouselImagesStr=""; $firstCarouselImagesStr="";
 						$hasDefaultBool=false; $countNum=0;
 						foreach($dbProductData['product_images'] as $product_images){
 							if($product_images["default"]=="yes"){
 								$hasDefaultBool=true;
 							}
 						}
 						foreach($dbProductData['product_images'] as $product_images){
 							$countNum++;
 							$imageExtension="";
                			$pos = strrpos($product_images['name'], ".");
							if ($pos !== false) {
    							$imageExtension=substr($product_images['name'],intval($pos)+1) ;
    						}
    						if($imageExtension!="" && $product_images["uuid"]!=""){ 	
    							$pathStr=PRODUCT_IMAGE_DIRECTORY.$product_images["uuid"].".".$imageExtension;							
 								if($hasDefaultBool){
									if($product_images["default"]=="yes"){
										$defaultImage=$pathStr;
										$defaultImageUUIDStr=$product_images["uuid"];
										$firstCarouselImagesStr='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$pathStr.'" data-largeimg="'.$pathStr.'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$pathStr.'" data-largeimg="'.$pathStr.'"/></a></li>';
									}
								}else{
									if($countNum==1){
 										$defaultImage=$pathStr;
 										$defaultImageUUIDStr=$product_images["uuid"];
										$firstCarouselImagesStr='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$pathStr.'" data-largeimg="'.$pathStr.'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$pathStr.'" data-largeimg="'.$pathStr.'"/></a></li>';
									}
								}
							}else{
								if($hasDefaultBool){
									if($product_images["default"]=="yes"){
										$defaultImage=$product_images["path"];
										$defaultImageUUIDStr=$product_images["uuid"];
										$firstCarouselImagesStr='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}else{
									if($countNum==1){
 										$defaultImage=$product_images["path"];
 										$defaultImageUUIDStr=$product_images["uuid"];
										$firstCarouselImagesStr='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img id="'.$product_images["uuid"].'" onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}
							}
 						}
 						$carouselStr=$firstCarouselImagesStr.$carouselImagesStr;
 					?>
					<span class="demowrap"><img id="main_image" src="<?php echo $defaultImage; ?>" class="<?php echo $defaultImageUUIDStr; ?>" onerror="this.src='images/default-product-large.png'"/></span>
                    <a href='javascript:void(0)' onClick='pop_up()' title='View Full Image' style="padding: 8px 8px; margin-top:5px;" class='btn btn-danger'>View Full Image</a> 
						<?php if($carouselStr!=""){ ?>
						<ul id="imagesCarousel" class="elastislide-list">
							<?php echo $carouselStr; ?>			
						</ul>
						<?php } ?>
                    
					<?php } ?>
					<!--demo3--> 
				
          		</div>
         		<?php }else{ ?>
         		<div class="col-md-8 "> 
         			<span class="demowrap"><img id="main_image" src="images/default-product-large.png" class="" /></span>
         		</div>
         		<?php } ?>
         		<div class="col-md-4">
           			<!--<h1 class="producttitle"><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo $dbProductData['ProductName'];	}?></h1>           
        			<!--<p> Product Code:<strong> DF-3250</strong> <br/>-->
        			<?php if(isset($dbProductData['sku']) && $dbProductData['sku']!=""){ ?>
        				<p> Product Code:<strong> <?php echo $dbProductData['sku']; ?></strong> <br/>
        			<?php } ?>
        			<?php if(isset($dbProductData['stock_available']) && $dbProductData['stock_available']>0){ ?>
        				Availability:<strong> In stock</strong></p>
        			<?php }else{ ?>
        				Availability:<strong> Out of stock</strong> (Call Us)</p>
        			<?php } ?>
            		<div class="price-block">
            			<?php if(isset($dbProductData['Unit_Price']) && $dbProductData['Unit_Price']>0){ ?>
            			<span class="price"><?php echo CURRENCY." ".$dbProductData['Unit_Price']; ?></span>
            			<?php } ?>
            			<!-- <br/><span>Tax: £100.00</span> -->
            		</div>
            		<?php if(isset($QuickbodyStr) && $QuickbodyStr!=""){ ?>
             			<div class="quick-overview-blk">
             				<h4>Quick Overview</h4>
             				<p><?php echo $QuickbodyStr; ?></p>
             			</div>
             		<?php	} ?>
					<div CLASS="row">
		   				<div CLASS="col-md-6">
		   					<a href="mailto:?subject=Product from the <?php echo SITE_NAME; ?>&body=<?php echo SITE_WS_PATH.'product-'.$dbProductData['product_code'].'.html'; ?>" title="Email this page" STYLE="width:100%; margin-bottom:10px;"  class="btn btn-default btn-xs"><i CLASS="fa fa-envelope"></i> Email this page</a>
		   				</div>
						<div CLASS="col-md-6" style="padding-left: 0px;">
							<a data-toggle="modal" data-backdrop="static" href="#modal_form" class="btn btn-default btn-xs" STYLE="width:100%; margin-bottom:10px;"><i CLASS="fa fa-link"></i> Link this page</a>
		   				</div>
		   			</div>
		   			
             		<?php if(isset($dbProductData['product_option_available']) && count($dbProductData['product_option_available'])>0){  ?>
              		<div class="avilable-option-blk">
             			<h3>Available Options</h3>
             			<?php foreach($dbProductData['product_option_available'] as $option_ava){
             				if(isset($option_ava['values']) && count($option_ava['values'])>0){	 ?>
             				<div class="margin-btm10">
             					<sup class="red-txt margin-btm10">*</sup><?php echo $option_ava['name']; 	?>
             				</div>
             				<?php 
             					foreach($option_ava['values'] as $key => $ava_values){	?>
            					<label><input name="<?php echo $option_ava['uuid']; ?>" id="<?php echo $option_ava['name']; ?>" class="available_options" value="<?php echo $ava_values; ?>" type="radio" class="margin-right15" >&nbsp;<?php echo $ava_values; ?></label><br/>
               				<?php }
               				} ?>
               			<?php } ?> 			
          			</div>
          			<?php } ?>
            		<!--<input type="text" value="1" class="qty-bx">-->
            		<div CLASS="row">
            			<?php if(isset($dbProductData['Unit_Price']) && $dbProductData['Unit_Price']>0){ ?>
            				<div CLASS="col-sm-3"><input class="form-control text-center" value="1"  min="1" type="number" id="qunatityID" style="height: 43px;margin-bottom: 13px;border-radius: 0px;"></div>
           					<div CLASS="col-sm-9 pading-lft" id="productPageCartID" ><a href="javascript:void(0)" onClick="add_user_preferences('<?php echo $dbProductData['uuid']; ?>', 'cart'); return false;" class="add-to-cart-btn"><i class="fa fa-cart-plus"></i> Add to cart</a></div>
            			<div class="col-sm-4 pading-lft displayCartsClass" style="display:none;">
            			<?php }else{ ?>
            			
            			<div class="col-sm-12 displayCartsClass" style="display:none;">
            			<?php } ?>
            				<a href="cart.htm?<?php echo NewGuid();?>" class="btn btn-danger btn-sm " style=" border-radius:1px; line-height:30px; margin-bottom:10px; position: relative; width:100%;">
            					View cart<span class="cart_items cartItemsClass">0</span>
            				</a>
            			</div>

            		</div>
            		
            		<div CLASS="row whishlistMsg">
		   				
            		<?php if(isset($dbProductData['product_code']) && $dbProductData['product_code']!=""){  ?>
            			<div CLASS="col-sm-6"><a href="javascript:void(0)" onClick="enquire_about('code', '<?php echo $dbProductData['product_code'];?>')" class="buy-now-btn">Enquire Now</a></div>
            		<?php }elseif(isset($dbProductData['uuid']) && $dbProductData['uuid']!=""){	?>
						<div CLASS="col-sm-6"><a href="javascript:void(0)" onClick="enquire_about('uuid', '<?php echo $dbProductData['uuid'];?>')" class="buy-now-btn">Enquire Now</a></div>
	          		<?php } ?>
            		<?php if(isset($_COOKIE["DreamFurnishingVisitor"]) && $_COOKIE["DreamFurnishingVisitor"]!=""){
            			if($dbfavData = $mongoCRUDClass->db_findone("session", array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"]), "ip_address" => __ipAddress() ))){
            				$productexistBool=false;
            				if(isset($dbfavData['wishlist_products'])){
								foreach($dbfavData['wishlist_products'] as $wishlistProds)  {   
									if($wishlistProds['uuid']==$dbProductData['uuid']){
										$productexistBool=true;
										break;
									}
								}
							}
            				if($productexistBool) {	?>
								<div CLASS="col-sm-6 pading-lft" ><span id="wishlistBtn"><a href="javascript:void(0)" onClick="remove_user_preferences('<?php echo $dbProductData['uuid']; ?>', 'wishlist')" class="buy-now-btn" title="Remove from wishlist"> Remove from <i class="glyphicon glyphicon-heart"></i></a></span></div>
							<?php }else{	?>
								<div CLASS="col-sm-6 pading-lft" ><span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_user_preferences('<?php echo $dbProductData['uuid']; ?>', 'wishlist')" class="buy-now-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span></div>
						<?php	}
            			}else{
            		?>
            			<div CLASS="col-sm-6 pading-lft" ><span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_user_preferences('<?php echo $dbProductData['uuid']; ?>', 'wishlist')" class="buy-now-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span></div>
            		<?php } 
            		}else{ ?>
            			<div CLASS="col-sm-6 pading-lft" ><span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_user_preferences('<?php echo $dbProductData['uuid']; ?>', 'wishlist')" class="buy-now-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span></div>
            		<?php } ?>
            		</div>
            	<?php if(isset($dbProductData['Unit_Price']) && $dbProductData['Unit_Price']>0){ 	
            			if(isset($dbProductData['stock_available']) && $dbProductData['stock_available']>0){ ?>
        				<div class="bg-info " style="margin-top:15px; margin-bottom:15px; padding:10px;"> 
       						<ul class="fa-ul margintop10">
              					<li><i class="fa-li fa fa-truck"></i>Delivery : 5-10 working days</li>
            				</ul>
            			</div>
        			<?php }else{ ?>
        				<div class="bg-info " style="margin-top:15px; margin-bottom:15px; padding:10px;"> 
       						<ul class="fa-ul margintop10">
              					<li><i class="fa-li fa fa-truck"></i>Delivery : 4-6 weeks</li>
            				</ul>
            			</div>
        			<?php }
        			} ?>
            		
         		</div>
        	</div>
		</div>
      	<div class="col-md-12 prduct-desc-bx">
			<div class="row clearfix">
         		<div class="col-md-12 ">
         			<?php $specNum=0; $specificationStr="";
                		
         			if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!=""){	
                		foreach($dbProductData['virtual_fields'] as $virtual_fields){	
                  			if(isset($virtual_fields["name"]) && $virtual_fields["name"]!="" && isset($virtual_fields["value"]) && $virtual_fields["value"]!=""){	
                                $specValueStr=$virtual_fields["value"];
                                $specNameStr=strtolower($virtual_fields["name"]);
                                if($specNameStr=='width' || $specNameStr=='height' || $specNameStr=='depth' ){
                                    $tempSpecValueStr = trim($specValueStr);
                                    $tempSpecValueStr = (int)$tempSpecValueStr * 0.3937;
                                    $tempSpecValueStr = $tempSpecValueStr.' in';
                                    $specValueStr = $specValueStr.' cms&nbsp;&nbsp;('.$tempSpecValueStr.')';
                                }
                                /**$pos = strpos($specValueStr, 'cms');
                                if ($pos !== false) {
                                    $tempSpecValueStr= substr($specValueStr,0,$pos);
                                    $tempSpecValueStr = trim($tempSpecValueStr);
                                    $tempSpecValueStr = (int)$tempSpecValueStr * 0.3937;
                                    $tempSpecValueStr = $tempSpecValueStr.' in';
                                    $specValueStr = $specValueStr.'&nbsp;&nbsp;('.$tempSpecValueStr.')';
                                }**/
                                
                    			$specificationStr.= '<tr><td>'.$virtual_fields["name"].'</td><td>'.$specValueStr.'</td></tr>';
                    			$specNum++;
                    		}
                    	}
         			}
         			?>
            		<ul class="nav nav-tabs">
              			<li class="active"><a href="#description" data-toggle="tab">Description</a></li>
              			<?php  if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!="" && $specNum>=1 && $specificationStr!=""){	?>
              			<li><a href="#specification" data-toggle="tab">Specification</a></li>
              			<?php } ?>
              			<!-- <li><a href="#faq" data-toggle="tab">FAQ</a></li> -->
            		</ul>
            
            		<!-- Tab panes -->
            		<div class="tab-content">
              			<div class="tab-pane active" id="description">
                			<div class="tabcontent">
                			<?php if(isset($dbProductData['Description']) && $dbProductData['Description']!=""){ 
                				echo $dbProductData['Description'];
                			}	?>
                            <?php  if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!="" && $specNum>=1 && $specificationStr!=""){ ?>
                                <table class="table table-bordered table-hover">
                  					<thead>
                    					<tr>
                      						<th style="background-color: #20295d;color: #fff;height: 40px;line-height: 30px;border-bottom: 0px;">Dimensions</th>
                      						<th style="background-color: #20295d;color: #fff;height: 40px;line-height: 30px;border-bottom: 0px;">Values</th>
                    					</tr>
                  					</thead>
                  					<tbody>
                  					<?php  echo $specificationStr;	?>
                    				</tbody>
                				</table>
                            <?php } ?>
                			</div>
                		</div>
                		<?php  if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!="" && $specNum>=1 && $specificationStr!=""){ ?>
             				<div class="tab-pane" id="specification">
             					<table class="table table-bordered table-hover">
                  					<thead>
                    					<tr>
                      						<th style="background-color: #20295d;color: #fff;height: 40px;line-height: 30px;border-bottom: 0px;">Dimensions</th>
                      						<th style="background-color: #20295d;color: #fff;height: 40px;line-height: 30px;border-bottom: 0px;">Values</th>
                    					</tr>
                  					</thead>
                  					<tbody>
                  					<?php  echo $specificationStr;	?>
                    				</tbody>
                				</table>
                			</div>
                		<?php }	?>
              			<!--<div class="tab-pane" id="faq">
             				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sagittis volutpat eros eu faucibus. Curabitur facilisis, ante id consectetur sodales, tortor erat mollis ligula, vitae semper tortor diam id nulla. Aliquam sit amet odio congue, sagittis tortor eget, convallis nisi. Suspendisse in erat pharetra, posuere dolor non, congue metus. Nullam dapibus ligula nec sem condimentum, ac ultricies dio cursus. Proin imperdiet fermentum metus in elementum. Nam eu nisl sit amet diam lacinia fermentum eu ut nisl. Quisque ut dui sit amet dui fermentum rutrum. Quisque quis cursus orci. Duis interdum fermentum nisi, sit amet lobortis metus dictum in.</p>
              			</div>-->
            		</div>
          		</div>
        	</div>
      	</div>
   	</div>
</div>
<div class="modal fade" id="modal_form" role="dialog">
	<div class="modal-dialog">
    	<!-- Modal content-->
      	<div class="modal-content">
        	<div class="modal-header">
          		<button type="button" class="close" data-dismiss="modal">&times;</button>
          		<h4 class="modal-title">Link To This Page</h4>
        	</div>
        	<div class="modal-body">
        		<span class="label label-default" style="white-space:normal;">Just copy the linking code into your your web page or blog and customize as you see fit...</span>
				<div class="formSep">
					<label for="textLink" class="control-label">
						Text Link
					</label>
					<span style="float:right;">
    					<a href="javascript:void(0)" onclick="$('#textLink').select()">select all</a>
					</span>
					<textarea class="form-control" style="margin-bottom:5px;" name="textLink" id="textLink" onClick="$('#textLink').select()" rows="2" ><a href="<?php echo SITE_WS_PATH; if(isset($dbProductData['product_code'])){ echo 'product-'.$dbProductData['product_code'].'.html'; } ?>">Product <?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!="") { echo $dbProductData['ProductName']; }?> from the <?php echo SITE_NAME;	?></a></textarea>
				</div>
				<?php if(isset($defaultImage) && $defaultImage!=""){ ?>
					<div class="formSep" >
					<label for="thumbLink" class="control-label">
					Thumbnail Image with Link
					</label>
					<span style="float:right;">
   						<a  href="javascript:void(0)" onClick="$('#thumbLink').select()">select all</a>
					</span>
						<textarea class="form-control" style="margin-bottom:5px;" name="thumbLink" id="thumbLink" onClick="$('#thumbLink').select()" rows="2" ><a href="<?php echo SITE_WS_PATH; if(isset($dbProductData['product_code'])){ echo 'product-'.$dbProductData['product_code'].'.html'; } ?>" ><img src="<?php echo SITE_PATH.'/'.$defaultImage; ?>" alt="Product <?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!="") { echo $dbProductData['ProductName']; }?> from the <?php echo SITE_NAME;	?>" height="150" width="150" /></a></textarea>
					</div>
				<?php } ?>
        	</div>
        	<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
    </div>
</div>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script type="text/javascript" src="js/jquery.imagezoom.min.js"></script>
<script type="text/javascript" src="js/modernizr.custom.17475.js"></script>
<script type="text/javascript" src="js/jquery.elastislide.js"></script>
<script type="text/javascript">
function pop_up(){
    var imgsrc=$('#main_image').attr('class'); 
    if(imgsrc!=''){
        var srclink='image-preview-'+imgsrc+'.html';
        window.open(srclink,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=668,directories=no,location=no');
    }
}
function remove_user_preferences(id, actionStr){
	$(".alert").remove();
	$.ajax({
		url: 'removeUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : id,  "action" : actionStr },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				var spanstr= '<a href="javascript:void(0)" onClick="add_user_preferences(\''+id+'\', \'wishlist\')" class="buy-now-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a>';
				$("#wishlistBtn").html("");
				$("#wishlistBtn").html(spanstr);
				$(".whishlistMsg").after('<div class="alert alert-success" role="alert">'+response.success+'</div>');
				fetchUserPreferences(actionStr);
			}else if(response.error){
				$(".whishlistMsg").after('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
		}
	});
}

function add_user_preferences(id, actionStr){
	var quantityNum= $("#qunatityID").val();
	var unitPriceOfProduct= $("#unitPriceOfProduct").val();
	$(".alert").remove();
	
	//product specifications
	var availableOptionsObj= new Array, j=0;
	$('.available_options').each(function(){
		if ($(this).prop('checked')) {
		var subspecObj= {};
		var availableOptionName=$(this).attr('id');
		var availableOptionVal=$(this).val();
		subspecObj[availableOptionName]=availableOptionVal;
		availableOptionsObj[j]=subspecObj;
		j++;
		}
	});	
	var availableOptions=JSON.stringify(availableOptionsObj);
	
	$.ajax({
		url: 'addUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : id, "action" : actionStr, "availableOptions" : availableOptions, "unit_price" : unitPriceOfProduct, "quantity" :quantityNum, "random" : Math.random() },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				if(actionStr=="wishlist"){
					$("#wishlistBtn").html("");
					var spanstr= '<a href="javascript:void(0)" onClick="remove_user_preferences(\''+id+'\', \'wishlist\')" class="buy-now-btn"> Remove from <i class="glyphicon glyphicon-heart"></i></a>';
					$("#wishlistBtn").html(spanstr);
				}
				$(".whishlistMsg").after('<div class="alert alert-success" role="alert">'+response.success+'</div>');
			}else if(response.error){
				$(".whishlistMsg").after('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}	
			fetchUserPreferences(actionStr);			
		}
	});
	
}
	
	$(function(){
		var carsousel = $('#imagesCarousel').elastislide({start:0,minItems:3,
			onClick:function( el, pos, evt ) {
				el.siblings().removeClass("active");
				el.addClass("active");
				carsousel.setCurrent( pos );
				evt.preventDefault();
				var imgSrc=el.find('img').attr('src');
				
				var demo2obj = $('#main_image').data('imagezoom');
				$("#main_image").attr("class", el.find('img').attr('id'));
				demo2obj.changeImage(imgSrc,el.find('img').data('largeimg'));
				//$('#demo2').ImageZoom({bigImageSrc:imgSrc});
			},
			onReady:function(){
				<?php if($defaultImage!=""){ ?>
				var defaultImgSrc=$("#main_image").attr("src");
				$('#main_image').ImageZoom({bigImageSrc:defaultImgSrc});
				<?php } ?>
				$('#imagesCarousel li:eq(0)').addClass('active');
			}
		});
	});

</script>
</body>
</html>