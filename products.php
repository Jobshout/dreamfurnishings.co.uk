<?php 
require_once("include/config_inc.php");
require_once("include/main_header.php");
$keyword= isset($_POST['keyword']) ? $_POST['keyword'] : "";
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
		
      		</div>
      		<div style="text-align:center">
      			<a href="javascript:void(0);" onclick="load_more_records()" title="Show more products" id="load_more_btn" class="btn btn-danger btn-sm" style="display:none; width:60%; margin-bottom:10px;">
				Show more products
				</a>
       		</div>
        	<div id="img_loading_div" style="text-align:center">
				<img src="images/loadersofa.gif"><br>
				Loading products...
			</div>
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

function load_more_records(){
	$('#load_more_btn').hide();
	$('#img_loading_div').show();
	start=end;
	end=start+nPageSize;
	load_data();
}

function fetch_cat_products(e){
	$('#products_section').html("");
	$('#load_more_btn').hide();
	$('#img_loading_div').show();
	category=e;
	keyword="";
	start=0;
	end=start+nPageSize;
	load_data();
}

function load_data(){
alert("Load data function called");
var jsonRow="return_products.htm?start="+start+"&limit="+nPageSize+"&category="+category+"&keyword="+keyword;
if(xhr) xhr.abort();
xhr=$.getJSON(jsonRow,function(result){
	alert("Got response by ajax request");
	totalNum=result.iTotalRecords;
	if(totalNum==0){
		$('#products_section').html('<div class="alert alert-danger" role="alert">Sorry, no products found!</div>');
		$('#load_more_btn').hide();
		$('#img_loading_div').hide();
	}else{
		if(result.error){
			$('#products_section').html('<div class="alert alert-danger" role="alert">Sorry, no products found!</div>');
			$('#load_more_btn').hide();
			$('#img_loading_div').hide();
		}else{
			if(totalNum>0 && result.iTotalReturnedRecords>0){
				if(result.breadcrumb){
					var breadCrumbStr='<li><a href="index.htm" title="Home">Home</a></li><li><a href="products.htm" title="List All Products">Products</a></li>';
					 breadCrumbStr+=result.breadcrumb;
					 $('.breadcrumb').html(breadCrumbStr);
				}
				var htmlStr="";
				$.each(result.aaData, function(i,item){
					htmlStr+='<div class="col-sm-6 col-md-6 col-lg-4 column">';
          			htmlStr+='<div class="productbox">';
          			var linkStr=gb_fn_linkHandlerJS('product.htm?uuid='+item.id,'product.htm?uuid='+item.id, linkHandlerBool);
          			var buyStr="contact.htm?u="+item.id;
          			if(item.code){
          				linkStr=gb_fn_linkHandlerJS('product-'+item.code+'.html','product.htm?code='+item.code, linkHandlerBool);
						buyStr="contact.htm?c="+item.code;
          			}
            		htmlStr+='<div style="background:#fff;"><a href="'+linkStr+'" title="'+item.name+'"><img src="'+item.image+'" class="img-responsive prdt-listing-pg-img"  alt="'+item.name+'" onerror="this.src=\'images/default-product-large.png\'"></a></div>';
              		htmlStr+='<div class="producttitle">  <a href="'+linkStr+'" title="'+item.name+'">'+item.name+'</a></div>';
               	 	htmlStr+='<div class="pull-right"><a href="'+linkStr+'" title="View full details" role="button"><span class="glyphicon glyphicon-info-sign" style="font-size:16px;"></span></a>';
               	 	htmlStr+='<a href="javascript:void(0)" onClick="alterWishlist(\''+item.id+'\')" title="Wishlist" class="wclass_'+item.id;
               	 	if(item.fav==true || item.fav=="true"){
               	 	htmlStr+=' whishlist_sel';
               	 	}
               	 	htmlStr+='"><i class="glyphicon glyphicon-heart"></i></a></div>';
                	htmlStr+='<div class="pricetext"><STRONG>Code: </STRONG>'+item.sku+'</div>';
                	htmlStr+='</div>';
               		htmlStr+='</div>';
				}); 
				
				$('#products_section').append(htmlStr);
				if(end< totalNum){
					$('#load_more_btn').show();
				}
				$('#img_loading_div').hide();
			}else{
				$('#load_more_btn').hide();
				$('#img_loading_div').hide();
			}
		}
	}
});
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
	load_data();
	$(window).scroll(function(){
		if ($(window).scrollTop() == $(document).height() - $(window).height()){
			if(xhr.status==200 && end < totalNum) {
				$('#load_more_btn').hide();
				$('#img_loading_div').show();
				start=end;
				end=start+nPageSize;
				load_data();
			}
		}
	});
});
</script>
</body>
</html>