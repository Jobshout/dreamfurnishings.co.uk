<?php 
require_once("include/config_inc.php");
$defaultImage="";
if(!isset($code)) { $code = isset($_GET['code']) ? $_GET['code'] : ''; $code = str_replace(".html", "", $code); }
if(!isset($uuidStr)) { $uuidStr = isset($_GET['uuid']) ? $_GET['uuid'] : ''; }

if(isset($code) && $code!=''){
	$dbProductData = $db->Products->findOne(array("code" => $code));
}elseif(isset($uuidStr) && $uuidStr!=''){
	$dbProductData = $db->Products->findOne(array("uuid" => $uuidStr));
}else{
	header("location:404.php");
	exit;
}
require_once("include/main_header.php"); ?>
<link rel="stylesheet" href="css/jquery.jqzoom.css" type="text/css">
</head>
<body>
<?php require_once("include/header.php"); ?>
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo $dbProductData['ProductName'];	}?></h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a onclick="gb_fn_linkCacheHandlerJS('index.php','index.php')" href="javascript:void(0)" title="Home">Home</a></li>
							<li class="active"><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo $dbProductData['ProductName'];	}?></li>
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
 						$carouselImagesStr=""; $firstCarouselImagesStr="";$countImagesNum=0;
 						foreach($dbProductData['product_images'] as $product_images){
 							$countImagesNum++;
 							if(isset($product_images["encoded_image"]) && $product_images["encoded_image"]!=""){
 								$imagebase64=$product_images["encoded_image"];
 								$imgdata = base64_decode($imagebase64);
								$mimetype = getImageMimeType($imgdata);
								$imageSrc="data:image/".$mimetype.";base64,".$imagebase64;
 								// remove default image check
								$defaultImage=$imageSrc;
								if($countImagesNum==1){
									$carouselImagesStr.='<a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \''.$imageSrc.'\',largeimage:  \''.$imageSrc.'\'}"><img src=\''.$imageSrc.'\'></a></li>';
								}else{
									$carouselImagesStr.='<a href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \''.$imageSrc.'\',largeimage:  \''.$imageSrc.'\'}"><img src=\''.$imageSrc.'\'></a></li>';
								}
							}else{
								$defaultImage=$product_images["name"];
								if($countImagesNum==1){
									$carouselImagesStr.='<a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \''.PRODUCT_DIR.$product_images["name"].'\',largeimage:  \''.PRODUCT_DIR.$product_images["name"].'\'}"><img src=\''.PRODUCT_DIR.$product_images["name"].'\'></a></li>';
								}else{
									$carouselImagesStr.='<a href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \''.PRODUCT_DIR.$product_images["name"].'\',largeimage:  \''.PRODUCT_DIR.$product_images["name"].'\'}"><img src=\''.PRODUCT_DIR.$product_images["name"].'\'></a></li>';
								}
							}
 						}
 						//$carouselStr=$firstCarouselImagesStr.$carouselImagesStr;
 						$carouselStr=$carouselImagesStr;
 					?>
 					<a href="<?php echo $defaultImage; ?>" class="jqzoom" rel='gal1'  title="triumph" >
            			<img src="<?php echo $defaultImage; ?>"  title="triumph"  style="border: 4px solid #666;">
       				</a>
					
					<?php if($carouselStr!=""){ ?>
					<ul id="thumblist" class="clearfix" >
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
           			<h1 class="producttitle"><?php if(isset($dbProductData['ProductName']) && $dbProductData['ProductName']!=""){ echo $dbProductData['ProductName'];	}?></h1>           
        			<!--<p> Product Code:<strong> DF-3250</strong> <br/>-->
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
             		<div class="quick-overview-blk">
             			<h4>Quick Overview</h4>
             			<?php if(isset($dbProductData['Description']) && $dbProductData['Description']!=""){ 
             				$bodyStr= $dbProductData["Description"];
							$firstSPosNum=stripos($bodyStr,"<p>");
							if ($firstSPosNum !== false) {
								$firstEPosNum=stripos($bodyStr,"</p>");
								$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
								$bodyStr=strip_tags($bodyStr);
								if(strlen($bodyStr)>125){
									$bodyStr=substr($bodyStr,0,125)."...";
								}
							}
							if(strlen($bodyStr)>125){
								$bodyStr=substr($bodyStr,0,125)."...";
							}
             			?>
             			<p><?php echo $bodyStr; ?></p>
             			<?php } ?>
             		</div>
             		<?php if(isset($dbProductData['product_option_available']) && $dbProductData['product_option_available']>0){  ?>
              		<div class="avilable-option-blk">
             			<h3>Available Options</h3>
             			<?php foreach($dbProductData['product_option_available'] as $option_ava){ ?>
             				<div class="margin-btm10">
             					<sup class="red-txt margin-btm10">*</sup><?php echo $option_ava['name']; 	?>
             				</div>
             				<?php if(isset($option_ava['values']) && $option_ava['values']!=""){	
             					foreach($option_ava['values'] as $key => $ava_values){	?>
            					<label><input name="<?php echo $option_ava['uuid']; ?>" type="radio" class="margin-right15" ><?php echo $ava_values; ?></label><br/>
               				<?php }
               				} ?>
               			<?php } ?>
          			</div>
          			<?php } ?>
            		<!--<input type="text" value="1" class="qty-bx">-->
            		<a href="#" class="buy-now-btn whishlistMsg">BUY Now</a>
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
            		<ul class="nav nav-tabs">
              			<li class="active"><a href="#description" data-toggle="tab">Description</a></li>
              			<li><a href="#specification" data-toggle="tab">Specification</a></li>
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
             			<div class="tab-pane" id="specification">
             				<?php  if(isset($dbProductData['virtual_fields']) && $dbProductData['virtual_fields']!=""){	?>
                				<table class="table table-condensed table-hover">
                  					<thead>
                    					<tr>
                      						<th>Key</th>
                      						<th>Values</th>
                    					</tr>
                  					</thead>
                  					<tbody>
                  					<?php foreach($dbProductData['virtual_fields'] as $virtual_fields){	?>
                    					<tr>
                      						<td><?php echo $virtual_fields["name"];?></td>
                      						<td><?php echo $virtual_fields["value"];?></td>
                    					</tr>
                    				<?php } ?>
                    				</tbody>
                				</table>
                			<?php } ?>
              			</div>
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
<script src="js/jquery-1.6.js" type="text/javascript"></script>
<script src="js/jquery.jqzoom-core.js" type="text/javascript"></script>
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
		$('.jqzoom').jqzoom({
			zoomType: 'innerzoom',
			preloadImages: false,
			alwaysOn:false
		});
	});

</script>
</body>
</html>