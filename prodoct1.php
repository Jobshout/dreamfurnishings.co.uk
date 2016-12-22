<?php 
require_once("include/config_inc.php");
$defaultImage=""; $referrerBreadCrumbStr="";
$referrerBreadCrumbLinkStr="";
if(!isset($code)) { $code = isset($_GET['code']) ? $_GET['code'] : ''; $code = str_replace(".html", "", $code); }
if(!isset($uuidStr)) { $uuidStr = isset($_GET['uuid']) ? $_GET['uuid'] : ''; }

if(isset($code) && $code!=''){
	$dbProductData = $db->Products->findOne(array("product_code" => $code));
	if(count($dbProductData)>0){
	}else{
		header("location:404.php");
		exit;
	}
}elseif(isset($uuidStr) && $uuidStr!=''){
	$dbProductData = $db->Products->findOne(array("uuid" => $uuidStr));
	if(count($dbProductData)>0){
	
	}else{
		header("location:404.php");
		exit;
	}
}else{
	header("location:404.php");
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
	$findpro  = 'products.php';
	$posIn = strpos($_SERVER["HTTP_REFERER"], $findpro);
	if ($posIn !== false) {
   		$referrerBreadCrumbLinkStr=$findpro;
   		$referrerBreadCrumbStr="Products";
	}
	$findwish   = 'wishlist.php';
	$posWish = strpos($_SERVER["HTTP_REFERER"], $findwish);
	if ($posWish !== false) {
		$referrerBreadCrumbStr="Wishlist";
   		$referrerBreadCrumbLinkStr=$findwish;
	}
}
require_once("include/main_header.php"); ?>
<link rel="stylesheet" href="css/imagezoom/imagezoom.css" />
<link rel="stylesheet" href="css/elastislide/es-cus.css" />
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
              				<li><a onclick="gb_fn_linkCacheHandlerJS('index.php','index.php')" href="javascript:void(0)" title="Home">Home</a></li>
							<?php if(isset($referrerBreadCrumbStr) && $referrerBreadCrumbStr!="" && isset($referrerBreadCrumbLinkStr) && $referrerBreadCrumbLinkStr!=""){	?>
							<li><a onclick="gb_fn_linkCacheHandlerJS('<?php echo $referrerBreadCrumbLinkStr; ?>','<?php echo $referrerBreadCrumbLinkStr; ?>')" href="javascript:void(0)" title="<?php echo $referrerBreadCrumbStr; ?>"><?php echo $referrerBreadCrumbStr; ?></a></li>
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
 							echo $_SERVER['DOCUMENT_ROOT'].$product_images['path'];
 							if(file_exists($_SERVER['DOCUMENT_ROOT'].$product_images['path'])){
 							echo "path";exit;
 							}else{
 							echo "encoded_image";exit;
 							}
 							if(isset($product_images["path"]) && $product_images["path"]!="" && file_exists($_SERVER['DOCUMENT_ROOT'].$product_images['path'])===true){
 								
 								if($hasDefaultBool){
									if($product_images["default"]=="yes"){
										$defaultImage=$product_images["path"];
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}else{
									if($countNum==1){
 										$defaultImage=$product_images["path"];
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}
							}elseif(isset($product_images["encoded_image"]) && $product_images["encoded_image"]!=""){
 								
 								$imagebase64=$product_images["encoded_image"];
 								$imgdata = base64_decode($imagebase64);
								$mimetype = getImageMimeType($imgdata);
								$imageSrc="data:image/".$mimetype.";base64,".$imagebase64;
								if($hasDefaultBool){
 									if($product_images["default"]=="yes"){
 										$defaultImage=$imageSrc;
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$imageSrc.'" data-largeimg="'.$imageSrc.'"/></a></li>';
									}else{
										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$imageSrc.'" data-largeimg="'.$imageSrc.'"/></a></li>';
									}
								}else{
									if($countNum==1){
										$defaultImage=$imageSrc;
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$imageSrc.'" data-largeimg="'.$imageSrc.'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$imageSrc.'" data-largeimg="'.$imageSrc.'"/></a></li>';
									}
								}
							}else{
								if($hasDefaultBool){
									if($product_images["default"]=="yes"){
										$defaultImage=$product_images["path"];
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}else{
									if($countNum==1){
 										$defaultImage=$product_images["path"];
										$firstCarouselImagesStr='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}else{
 										$carouselImagesStr.='<li><a href="#"><img onerror="this.src=\'images/default-product-large.png\'" src="'.$product_images["path"].'" data-largeimg="'.$product_images["path"].'"/></a></li>';
									}
								}
							}
 						}
 						$carouselStr=$firstCarouselImagesStr.$carouselImagesStr;
 					?>
					<span class="demowrap"><img id="main_image" src="<?php echo $defaultImage; ?>"  onerror="this.src='images/default-product-large.png'"/></span>
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
         			<span class="demowrap"><img id="main_image" src="images/default-product-large.png"  /></span>
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
        				Availability:<strong> Out of stock</strong></p>
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
             		<?php if(isset($dbProductData['product_option_available']) && $dbProductData['product_option_available']>0){  ?>
              		<div class="avilable-option-blk">
             			<h3>Available Options</h3>
             			<?php foreach($dbProductData['product_option_available'] as $option_ava){ ?>
             				<div class="margin-btm10">
             					<sup class="red-txt margin-btm10">*</sup><?php echo $option_ava['name']; 	?>
             				</div>
             				<?php if(isset($option_ava['values']) && $option_ava['values']!=""){	
             					foreach($option_ava['values'] as $key => $ava_values){	?>
            					<label><input name="<?php echo $option_ava['uuid']; ?>" class="available_options" value="<?php echo $option_ava['name'].': '.$ava_values; ?>" type="radio" class="margin-right15" >&nbsp;<?php echo $ava_values; ?></label><br/>
               				<?php }
               				} ?>
               			<?php } ?>
          			</div>
          			<?php } ?>
            		<!--<input type="text" value="1" class="qty-bx">-->
            		<?php if(isset($dbProductData['product_code']) && $dbProductData['product_code']!=""){  ?>
            			<a href="javascript:void(0)" onClick="enquire_about('code', '<?php echo $dbProductData['product_code'];?>')" class="buy-now-btn whishlistMsg">Enquire Now</a>
            		<?php }elseif(isset($dbProductData['uuid']) && $dbProductData['uuid']!=""){	?>
						<a href="javascript:void(0)" onClick="enquire_about('uuid', '<?php echo $dbProductData['uuid'];?>')" class="buy-now-btn whishlistMsg">Enquire Now</a>
	          		<?php } ?>
            		<?php if(isset($_COOKIE["DreamFurnishingVisitor"]) && $_COOKIE["DreamFurnishingVisitor"]!=""){
            			if($dbfavData = $db->session->findOne(array("_id" => new MongoId($_COOKIE["DreamFurnishingVisitor"]), "ip_address" => __ipAddress() ))){
            				$productexistBool=false;
							foreach($dbfavData['wishlist_products'] as $key=>$value)  {   
								if($value==$dbProductData['uuid']){
									$productexistBool=true;
									break;
								}
							}
							
            				if($productexistBool) {	?>
								<span id="wishlistBtn"><a href="javascript:void(0)" onClick="remove_wishlist('<?php echo $dbProductData['uuid']; ?>')" class="add-to-cart-btn"> Remove from Wishlist</a></span>
							<?php }else{	?>
								<span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_to_wishlist('<?php echo $dbProductData['uuid']; ?>')" class="add-to-cart-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span>
						<?php	}
            			}else{
            		?>
            			<span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_to_wishlist('<?php echo $dbProductData['uuid']; ?>')" class="add-to-cart-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span>
            		<?php } 
            		}else{ ?>
            			<span id="wishlistBtn"><a href="javascript:void(0)" onClick="add_to_wishlist('<?php echo $dbProductData['uuid']; ?>')" class="add-to-cart-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a></span>
            		<?php } ?>
        			<!--<div class="bg-info " style="margin-top:15px; margin-bottom:15px; padding:10px;"> 
        				<ul class="fa-ul margintop10"><li><i class="fa-li fa fa-truck"></i>Free Delivery</li></ul>
            		</div>-->
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
                    			$specificationStr= '<tr><td>'.$virtual_fields["name"].'</td><td>'.$virtual_fields["value"].'</td></tr>';
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
                			</div>
                		</div>
                		<?php  if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!="" && $specNum>=1 && $specificationStr!=""){ ?>
             				<div class="tab-pane" id="specification">
             					<table class="table table-condensed table-hover">
                  					<thead>
                    					<tr>
                      						<th>Key</th>
                      						<th>Values</th>
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
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script type="text/javascript" src="js/jquery.imagezoom.min.js"></script>
<script type="text/javascript" src="js/modernizr.custom.17475.js"></script>
<script type="text/javascript" src="js/jquery.elastislide.js"></script>
<script type="text/javascript">
function remove_wishlist(id){
	$(".alert").remove();
	$.ajax({
		url: 'removewishlist.php',
		type: 'POST',
		data: {"uuid" : id },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				var spanstr= '<a href="javascript:void(0)" onClick="add_to_wishlist(\''+id+'\')" class="add-to-cart-btn"> <i class="glyphicon glyphicon-heart"></i> Add to Wishlist</a>';
				$("#wishlistBtn").html("");
				$("#wishlistBtn").html(spanstr);
				$(".whishlistMsg").before('<div class="alert alert-success" role="alert">'+response.success+'</div>');
				fetchwishlist();
			}else if(response.error){
				$(".whishlistMsg").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
		}
	});
}

function add_to_wishlist(id){
	$(".alert").remove();
	$.ajax({
		url: 'addwishlist.php',
		type: 'POST',
		data: {"uuid" : id },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				$("#wishlistBtn").html("");
				var spanstr= '<a href="javascript:void(0)" onClick="remove_wishlist(\''+id+'\')" class="add-to-cart-btn"> Remove from Wishlist</a>';
				
				$("#wishlistBtn").html(spanstr);
				$(".whishlistMsg").before('<div class="alert alert-success" role="alert">'+response.success+'</div>');
				fetchwishlist();
			}else if(response.error){
				$(".whishlistMsg").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
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