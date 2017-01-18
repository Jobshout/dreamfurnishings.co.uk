<?php
require_once("include/config_inc.php");
require_once("include/main_header.php");
?>
</head>
<body>
<?php require_once("include/header.php"); ?>  	  
<section>
<div class="headingbcg " >
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-sm-8">
            <h1>
             Wishlist
            </h1>
          </div>
          <div class="col-md-4 col-sm-4 ">
		  <div class="text-right bred-crumb-xs clearfix">
            <ol class="breadcrumb ">
            <li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
			<li class="active">
                Wishlist
              </li>
            </ol>
			</div>
          </div>
        </div>
      </div>
    </div>

<div class="container">
	<div class="row content">
		<div class="col-md-12" id="products_section">
			
		</div>
		<div class="col-md-12" id="img_loading_div" style="text-align:center">
			<img src="images/loadersofa.gif"><br>
			Loading wishlist products...
		</div>
	</div>
</div>
        
</section>

<?php 
	require_once("include/top_footer.php");
	require_once("include/footer.php");
?>
<script>
var xhr;
function load_data(){
	var jsonRow="return_preferences_json.htm?action=wishlist";
	if(xhr) xhr.abort();
	xhr=$.getJSON(jsonRow,function(result){

		if(result.error){
			$('#products_section').html('<div class="alert alert-danger" role="alert">'+result.error+'</div>');
			$('#img_loading_div').hide();
		}else{
			var htmlStr="";		
			$.each(result.aaData, function(i,item){
				htmlStr+='<div CLASS="row wishlist-row" >';
				var linkStr=item.link;
          		var buyCodeStr=item.id, buyStr="uuid";
          		if(item.code){
          			buyCodeStr=item.code;
          			buyStr="code";
          		}
            	htmlStr+='<div class="col-sm-3 text-center"><a href="'+linkStr+'"><img src="'+item.image+'" class="img-responsive" alt="'+item.name+'" STYLE="height:170px;" onerror="this.src=\'images/default-product-small.png\'"></a></div>';
              	htmlStr+='<div class="col-sm-6"><a href="'+linkStr+'" class="prdt-name">'+item.name+'</a><br><strong>Product SKU</strong>: '+item.sku+'<br>';
              	htmlStr+='<span class="price">'+item.currency+item.price+'</span></div>';
              	htmlStr+='<div class="col-sm-3 text-right"><a onclick="enquire_about(\''+buyStr+'\', \''+buyCodeStr+'\')" href="javascript:void(0)" class="btn btn-danger" title="Enquire Now" style="margin-bottom:8px;"> <i CLASS="fa fa-envelope"></i> Enquire Now</a><br/>';
				htmlStr+='<a HREF="javascript:void(0)" onClick="remove_wishlist(\''+item.id+'\')" title="Remove from wishlist" class="btn btn-primary"><i CLASS="fa fa-remove"></i> Remove from wishlist</a></div>';
            	htmlStr+='</div>';
            }); 
			
			$('#products_section').append(htmlStr);
			$('#img_loading_div').hide();
		}
	});
}

function remove_wishlist(id){
	$(".alert").remove();
	$.ajax({
		url: 'removeUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : id, "action" : "wishlist"},
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				$('#products_section').html("");
				$('#img_loading_div').show();
				load_data();
				fetchUserPreferences("wishlist");
			}else if(response.error){
				$("#products_section").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
		}
	});
}
$(function () {
	load_data();	
});
</script>
</body>
</html>
