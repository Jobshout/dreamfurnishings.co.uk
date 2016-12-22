<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                

require_once("include/config_inc.php");
require_once("include/main_header.php");

$latestProducts = $db->Products->find(array('publish_on_web' => true, 'product_category' => array('$ne' => "")))->sort(array("created_timestamp" => -1))->limit(2);
?>

<link rel="stylesheet" type="text/css" href="css/rSlider.min.css" />
<style>
    .rSlider > .rSlider--dots-controls{
    display: none;
    }
</style>
</head>
<body>
<?php require_once("include/header.php"); ?>
<section style="position:relative;">
    <?php $sliderTokenStr=get_token_value('home-page-slider');
    if($sliderTokenStr!=""){	?>
        <?php echo $sliderTokenStr; ?>
    <?php }else{ ?>
	<div class='rSlider hidden-sm hidden-xs'>
		<div class='rSlider--view'>
			<div class='rSlider--slide'>
				<div class='rSlider--container'>
					<div class="slide-styled">
            			<h1>Style, Comfort, Quality, Selection!</h1>
            			<p>Open the door of happiness! 	</p>
           			</div>
				</div>

        		<div class='rSlider--image'><img src='images/slider-img-001.jpg' /></div>
        		<div class='rSlider--bg-color'></div>
			</div>
			<div class='rSlider--slide'>
				<div class='rSlider--container'>
					<div class="slide-styled">
            			<h1>Style, Comfort, Quality, Selection!</h1>
            			<p>Open the door of happiness! </p>
          			</div>
				</div>
				<div class='rSlider--image'><img src='images/slider-img-002.jpg' /></div>
        		<div class='rSlider--bg-color'></div>
			</div>
		</div>
		<div class='rSlider--dots-controls'></div>
   	</div>
    <?php } ?>
	<?php if($latestProducts->count()>0){  ?>
	<div class="container">
		<div class="latest-prdt-blk ">
        	<h2 class="bold-blu-hding">Latest Products</h2>
        	<div class="row">
        	<?php foreach($latestProducts as $latestProduct){
        			$bodyStr= getBriefText($latestProduct["Description"]);
						
						$defaultImage=findDefaultImage($latestProduct);
						if($defaultImage==""){
							$defaultImage="images/default-product-small.png";
						}
						
						/**if(isset($latestProduct['product_images']) && count($latestProduct['product_images'])>0){ 
       						foreach($latestProduct['product_images'] as $product_images){
    							if($product_images["default"]=="yes"){
 									//if(isset($product_images['path']) && $product_images['path']!="" && file_exists($product_images['path'])===true){ 
    								//	$defaultImage=$product_images["path"];
    								//	break;
    								//}elseif(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
    								if(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
 										$defaultBase64=$product_images["encoded_image"];
 										$imgdata = base64_decode($defaultBase64);
										$mimetype = getImageMimeType($imgdata);
										$defaultImage="data:image/".$mimetype.";base64,".$defaultBase64;
										break;
									}else{
										$defaultImage=$product_images["path"];
										break;
									}
								}else{
 									//if(isset($product_images['path']) && $product_images['path']!="" && file_exists($product_images['path'])===true){ 
    								//	$defaultImage=$product_images["path"];
    								//	break;
    								//}elseif(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
    								if(isset($product_images['encoded_image']) && $product_images['encoded_image']!=""){ 
 										$defaultBase64=$product_images["encoded_image"];
 										$imgdata = base64_decode($defaultBase64);
										$mimetype = getImageMimeType($imgdata);
										$defaultImage="data:image/".$mimetype.";base64,".$defaultBase64;
										break;
									}else{
										$defaultImage=$product_images["path"];
										break;
									}
								}
							}
						}**/
				?>
        		<div class="col-sm-6">
            		<div class="row">
                		<div class="col-md-5 col-sm-12 col-xs-12">
                			<a href="javascript:void(0)" onclick="gb_fn_linkCacheHandlerJS('<?php echo $latestProduct["product_code"].'.html';?>','product.php?code=<?php echo $latestProduct["product_code"];?>')">
                			<img src="<?php echo $defaultImage; ?>" class="img-responsive" onerror="this.src='images/default-product-small.png'">
              			</a>
                		</div>
                    	<div class="col-md-7 col-sm-12 col-xs-12">
                			<h4 class="feature-hding"><a href="javascript:void(0)" onclick="gb_fn_linkCacheHandlerJS('<?php echo $latestProduct["product_code"].'.html';?>','product.php?code=<?php echo $latestProduct["product_code"];?>')">
                				<?php echo ucfirst($latestProduct["ProductName"]);	?></a></h4>
                			<p><?php echo $bodyStr; ?></p>
               				<a href="javascript:void(0)" onclick="gb_fn_linkCacheHandlerJS('<?php echo $latestProduct["product_code"].'.html';?>','product.php?code=<?php echo $latestProduct["product_code"];?>')" class="view-more">Read more</a>
                    	</div>
                	</div>
            	</div>
            <?php }	?>
       		</div>
		</div>
	</div>
    <?php } ?>
</section>

<?php $latestCategories = $db->categories->find(array("is_active" => true))->sort(array("created_timestamp" => 1))->limit(3);
if($latestCategories->count()>0){ ?>
<section class="service-blk-bg">
<div class="container">
	<div class="offer-hding-blk">
		<h2>What we offer</h2>
		<p>Comfort and style at affordable price!</p>
     </div>   
     <div class="row">
     	<?php $i=0;
     	foreach($latestCategories as $fetchCat){
     		$i++;
     		//$imgSrc="images/bedroom.jpg";
     		$defaultImgSrc="images/".$fetchCat["code"].".jpg";
     		/**if($i==1){
     			$imgSrc="images/bedroom.jpg";
     		}elseif($i==2){
     			$imgSrc="images/living-room.jpg";
     		}elseif($i==3){
     			$imgSrc="images/dining-room.jpg";
     		}elseif($i==4){
     			$imgSrc="images/living-room.svg";
     		}	**/
     		?>
     	<div class="col-sm-4 ">
        	<div class="service-blk">
        		<a HREF="products.php?category=<?php echo $fetchCat["uuid"];?>"><img src="<?php echo $defaultImgSrc;?>"></a>
                <h4><?php echo $fetchCat["name"];?></h4>
                <?php if(isset($fetchCat["description"]) && $fetchCat["description"]!=""){ ?>
                <p><?php 
                	/**$bodyStr= $fetchCat["description"];
                	$firstSPosNum=stripos($bodyStr,"<p>");
					$firstEPosNum=stripos($bodyStr,"</p>");
					if($firstSPosNum>0 && $firstEPosNum>0){
					$bodyStr=substr($bodyStr,$firstSPosNum,$firstEPosNum);
					}
					$bodyStr=strip_tags($bodyStr);
					if(strlen($bodyStr)>125){
						$bodyStr=substr($bodyStr,0,125)."...";
					}**/
					$bodyStr= getBriefText($fetchCat["description"]);
                	echo $bodyStr;
                ?></p>
                <?php }	?>
                <div CLASS="view-product"><a HREF="products.php?category=<?php echo $fetchCat["uuid"];?>">View Products</a></div>
                
             </div>   
            
        </div>
        <?php } ?>
     </div>
</div>
</section>
<?php } ?>
<!-- /.container -->
<?php if($latestCategories->count()>0){ ?>
<div class="container" >
<?php }else{ ?>
<div class="container whybuy-blk" >
<?php }?>
<div class="row offers-blk">
<div class="col-sm-12">
<h2 class="blu-heding"><span>Why buy furniture at Dream Furnishings?</span></h2>
      
		</div>
		
		<div class="col-sm-6">
      		<div class="row">
           		 <div class="col-sm-5">
                 <img src="images/sure-warranty.jpg" class="img-responsive">
                 </div>
                  <div class="col-sm-7">
                 <h5 class="feature-hding">Warranty for sure</h5>
                 <p>Work kills, discounts don’t! Get designer furniture at affordable price right here.</p>
                 </div>
            </div>
		</div>
        <div class="col-sm-6">
        	<div class="row feature-blk">
           		 <div class="col-sm-5">
                 <img src="images/international-design.jpg" class="img-responsive">
                 </div>
                  <div class="col-sm-7">
                 <h5 class="feature-hding">International Designs</h5>
                 <p>Most of our products come with one year warranty. you got  no reason to worry!</p>
                 </div>
            </div>
		</div>

</div>
	<div class="row blogSectionClass" style="display:none;">
		<div class="col-xs-12" >
			<h2 class="section-hding"><span>From The Blog</span></h2>
     		<nav>
				<ul class="control-box pager" id="moveBlog">
					<li><a data-slide="prev" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-left"></i></a></li>
					<li><a data-slide="next" href="#myCarousel" class=""><i class="glyphicon glyphicon-chevron-right"></i></a></li>
				</ul>
			</nav>
    		<div class="carousel slide" id="myCarousel">
        		<div class="carousel-inner" id="contentofblog">
            
        		</div>
      		</div><!-- /#myCarousel -->
        
		</div><!-- /.col-xs-12 -->          
	</div>
</div><!-- /.container -->
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script src="js/rSlider.min.js"></script>

<script type="text/javascript">
function load_data(){
	$.getJSON("json-blogs.php",function(result){
		if(result.error){
			
		}else{
			var htmlStr="";
			var countNum=0, countLiNum=0;
			$.each(result.aaData, function(i,item){
				countNum++;
				countLiNum++;
				var activeClassStr="";
				if(countNum==1){
					activeClassStr="active";
				}
				if(countNum==1 || countLiNum==1){
					htmlStr+='<div class="item '+activeClassStr+'"><ul><div class="row">';
				}
				htmlStr+='<li class="col-sm-4 col-md-4 blog-listing-blk"><div class="blog-listing-img"><a onClick="gb_fn_linkCacheHandlerJS(\''+item.code+'.html\',\'content.php?code='+item.code+'\')" href="javascript:void(0)"><img src="'+item.image+'" class="img-responsive" alt=""></a></div>';
            	htmlStr+='<div class="caption"><div class="row"><div class="col-xs-3 col-sm-4 col-md-3"><div class="blog-pub-dt"><span class="date">'+item.date+'</span><span class="mnth">'+item.month+'</span></div>';
                htmlStr+='<div class="comments-count"> '+item.comments+'</div></div>';
                htmlStr+='<div class="col-xs-9 col-sm-8 col-md-9"><h3><a onClick="gb_fn_linkCacheHandlerJS(\''+item.code+'.html\',\'content.php?code='+item.code+'\')" href="javascript:void(0)">'+item.title+'</a></h3><p>'+item.body+'</p></div></div></div></li>';
        
        		if(countLiNum==3){
        			htmlStr+='</div></ul></div>';	
        		}	
        		if(countLiNum==3){
        			countLiNum=0;
        		}
			}); 
			//console.log(countNum);
			if(countNum>=4){
				$("#moveBlog").show();
			}else{
				$("#moveBlog").hide();
			}
			if(result.iTotalRecords>=1){
				$("#blogSectionClass").show();
			}else{
				$("#blogSectionClass").hide();
			}
			$('#contentofblog').html(htmlStr);
			
		}
	});
}

$(function () {
	load_data();
    
    //temporary slider
     function do_slide(){
        var i=1;
        setInterval(function(){
            if(i % 2 === 0 ){
               $('.rSlider--view').attr('style', 'margin-left: -1905px;');
            }else{
                $('.rSlider--view').attr('style', 'margin-left: 0px;');
            }
            i++;
        }, 6000);
    }
});
</script>
</body>
</html>