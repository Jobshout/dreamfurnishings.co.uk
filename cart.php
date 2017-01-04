<?php
require_once("include/config_inc.php");
require_once("include/main_header.php");

if(!empty($_POST['submit'])){
	if($isUserSignedInBool){
		if(isset($session_values) && $session_values["user_uuid"]!="" && $session_values["login_status"]==true){
			if(isset($_POST['items_subtotal']) && $_POST['items_subtotal']>=1){
				$session_update= array("checkout_state"=>0, "subtotal"=> $_POST['items_subtotal'], "total_tax" =>  $_POST['total_tax'], "tax_rate"=> $_POST['tax_rate'], "tax_code"=> $_POST['tax_code'], "total"=> $_POST['grand_total'], "discount"=>0); // checkout_state=1 confirmation of address, checkout_state=0 items are in cart
				if($mongoCRUDClass->db_update("session", array("_id" => $session_values['_id']), $session_update)){
					header("location:checkout.htm?".rand());
					exit;
				}
			}else{
				$err_msg="Add some products in your cart!";
			}
		}else{
			header("Location: checkout.htm?redirect=cart");
			exit;
		}
	}else{
		header("Location: checkout.htm?redirect=cart");
		exit;
	}
}
?>
<style>
    .rSlider > .rSlider--dots-controls{
    display: none;
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
            		<h1>Cart</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
             				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">Cart</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12">
					<?php if(isset($err_msg)){ ?>
			 			<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $err_msg; ?>
						</div>
					<?php }else if(isset($succ_msg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succ_msg; ?>
  						</div>
					<?php } ?>
				
				<div CLASS="table-responsive">
					<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" CLASS="table table-hover table-bordered cartTable mainContentClass">
						<TBODY>
							<TR>
								<th WIDTH="20%">&nbsp;</th>
								<th WIDTH="35%">Name</th>
								<th WIDTH="10%">Price</th>
								<th WIDTH="5%">Quantity</th>
								<th WIDTH="10%">Total</th>
								<th WIDTH="10%">Remove</th>
							</TR>
						
						</TBODY>
					</TABLE>
					
				</div>
				<div CLASS="row mainContentClass">
					<div CLASS="col-md-6">
						<p id="vatAppliedMsg" <?php if(HIDETAX){ ?>style="display:none;"<?php } ?> ></p>
					</div>
					<div CLASS="col-md-6">
						<div CLASS="table-responsive">
							<form name="cart" id="cart" method="post">
							<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" CLASS="table  table-bordered" style="background:#1f275e;color:#fff; padding:10px 0; margin-top:10px; ">
								<TBODY>
									<TR>
										<th ALIGN="RIGHT" CLASS="text-right"><strong>Subtotal <?php echo  CURRENCY;?><span id="subTotalHere">0</span></strong></th>
									</TR>
									<?php 
										$taxRate=0; $taxCode="";
										if(HIDETAX==false && isset($userLoggedIn['country']) && $userLoggedIn['country']!=""){
											if($countryAb = $mongoCRUDClass->db_findone("countries", array("name" => $userLoggedIn['country']))){
												$taxCode=$countryAb['WMO'];
												switch ($countryAb['WMO']) {
    												case "UK":
        												$taxRate=20;
        												break;
   	 												case "US":
       													$taxRate=0;
        												break;
    												default:
        												$taxRate=0;
        												break;
												}
									?>
									<TR <?php if(HIDETAX){ ?>class="display:none;"<?php } ?> >
										<th ALIGN="RIGHT" CLASS="text-right"><strong>Total Tax <?php echo  CURRENCY;?><span id="totalTaxHere">0</span></strong></th>
									</TR>
									<?php	}
									}	?>
									<TR>
										<th ALIGN="RIGHT" CLASS="text-right"><strong>Total <?php echo  CURRENCY;?><span id="grandTotalHere">0</span></strong></th>
									</TR>
									<TR>
										<td class="actions text-right" >
											<a HREF="products.htm" CLASS=" btn btn-default">Continue Shopping </a>
											<input id="items_subtotal" name="items_subtotal" type="hidden" value="0">
											<input id="tax_rate" name="tax_rate" type="hidden" value="<?php echo $taxRate; ?>">
											<input id="tax_code" name="tax_code" type="hidden" value="<?php echo $taxCode; ?>">
											<input id="total_tax" name="total_tax" type="hidden" value="0">
											<input id="grand_total" name="grand_total" type="hidden" value="0">
											<button type="submit" name="submit" class="btn btn-info" value="submit">Checkout <i class="fa fa-angle-right"></i></button>
										</td>
									</TR>
								</TBODY>
							</TABLE>
							</form>
						</div>
					</div>
				</div>
				
				<div class="col-md-12" id="img_loading_div" style="text-align:center">
					<img src="images/loadersofa.gif"><br>
					Loading cart...
				</div>
			</div>
		</div>
	</div>
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script>
$(function () {
	load_data();
	var taxRateNum=$("#tax_rate").val();
	if(taxRateNum>=1){
		$("#vatAppliedMsg").html(taxRateNum+"% VAT applied");
	}else{
		$("#vatAppliedMsg").hide();
	}	
});
var xhr;
function load_data(){
	var jsonRow="return_preferences_json.htm?action=cart";
	if(xhr) xhr.abort();
	xhr=$.getJSON(jsonRow,function(result){
		if(result.iTotalRecords==0){
			$(".mainContentClass").hide();
		}
		
		if(result.error){
			$('.cartTable').before('<div class="alert alert-danger" role="alert">'+result.error+'</div>');
			$('#img_loading_div').hide();
		}else{
			var htmlStr="";		
			$.each(result.aaData, function(i,item){
				htmlStr+='<tr id="'+item.id+'" >';
				var linkStr=gb_fn_linkHandlerJS('product.htm?uuid='+item.id,'product.htm?uuid='+item.id, linkHandlerBool);
          		var buyCodeStr=item.id, buyStr="uuid";
          		if(item.code){
          			linkStr=gb_fn_linkHandlerJS('product-'+item.code+'.html','product.htm?code='+item.code);
          			buyCodeStr=item.code;
          			buyStr="code";
          		}
          		
            	htmlStr+='<td><a href="'+linkStr+'"><img src="'+item.image+'" alt="'+item.name+'" onerror="this.src=\'images/default-product-small.png\'" style="height:170px;"></a></td>';
            	htmlStr+='<td><a href="'+linkStr+'" class="prdt-name">'+item.name+'</a><br><strong>Product SKU</strong>: '+item.sku;
            	if(item.options){
            		htmlStr+='<br><strong>Selected Option(s)</strong>: '+item.options;
            	}
            	htmlStr+='</td>';
            	htmlStr+='<td>'+item.currency+item.price+'<input id="unit_'+item.id+'" type="hidden" value="'+item.price+'"></td>';
            	var quantity = 1;
            	if(item.Quantity){
            		quantity = parseInt(item.Quantity);
            	}
              	htmlStr+='<td data-th="Quantity"><input class="form-control text-center" value="'+quantity+'" type="number" min="1" onChange="changeQuantity(\''+item.id+'\', this.value)"></td>';
              	var subtotalFloat=parseFloat(item.price)*quantity;
              	htmlStr+='<td>'+item.currency+'<span id="spanSB_'+item.id+'">'+subtotalFloat+'</span><input id="inputSB_'+item.id+'" type="hidden" value="'+subtotalFloat+'" class="subtotalClass"></td>';
              	htmlStr+='<td><a HREF="javascript:void(0)" onClick="remove_user_preferences(\''+item.id+'\')" title="Remove from cart" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a></td>';
            	htmlStr+='</tr>';
            }); 
			
			$('.cartTable').append(htmlStr);
			evaluateGrandTotal();
			$('#img_loading_div').hide();
		}
	});
}

function changeQuantity(id, val){
	var unit_price=parseFloat($("#unit_"+id).val());
	var subtotalFloat=parseFloat(unit_price)*val;
	$.ajax({
		url: 'updateUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : id, "quantity" : val, "unitPrice" : unit_price },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				$("#inputSB_"+id).val(subtotalFloat);
				$("#spanSB_"+id).html(subtotalFloat);
				evaluateGrandTotal();
			}else if(response.error){
				$(".cartTable").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
		}
	});
}

function remove_user_preferences(id, actionStr='cart'){
	$(".alert").remove();
	$.ajax({
		url: 'removeUserPreferences.htm',
		type: 'POST',
		data: {"uuid" : id,  "action" : actionStr },
		dataType: 'json',
		cache: false,
		success: function(response){
			if(response.success){
				$("#"+id).remove();
				evaluateGrandTotal();
				fetchUserPreferences(actionStr);
			}else if(response.error){
				$(".cartTable").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
			}				
		}
	});
}

function evaluateGrandTotal(){
	var subtotal=0, total_tax=0, grandTotal=0;
	var taxRateNum=parseFloat($("#tax_rate").val());
	
	$('.subtotalClass').each(function(){
		var itemUnitPrice= parseFloat($(this).val());
		subtotal=subtotal+itemUnitPrice;
	});
	subtotal=subtotal.toFixed(2);
	$("#subTotalHere").html(subtotal);
	$("#items_subtotal").val(subtotal);
	
	if(taxRateNum!=0){
		total_tax =subtotal*taxRateNum/100;
		total_tax=total_tax.toFixed(2);
	}
	
	grandTotal = parseFloat(subtotal)+parseFloat(total_tax);
	grandTotal=grandTotal.toFixed(2);  
	$("#grandTotalHere").html(grandTotal);
	$("#totalTaxHere").html(total_tax);
	
	$("#grand_total").val(grandTotal);
	$("#total_tax").val(total_tax);
}
</script>
</body>
</html>