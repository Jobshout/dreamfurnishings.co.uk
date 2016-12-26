<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");
if(isset($isUserSignedInBool) && $isUserSignedInBool==true){
	if(!isset($userLoggedIn)){
		header("Location: logout.php");
		exit;
	}
}else{
	header("Location: login.php?redirect=myaccount");
	exit;
}
$statusStr="";
$uuid= isset($_GET['uuid']) ? $_GET['uuid'] : '';
if($uuid!=""){
	if($orderDetails = $db->orders->findOne(array("uuid" => $_GET['uuid']))){
		switch ($orderDetails['status']) {
    								case 2:
        								$statusStr='<span class="alert-success">Completed</span>';
        								break;
    								case 2:
        								$statusStr='<span class="status-code2">Checkout form submitted, viewing confirmation page</span>';
        								break;
    								default:
        								$statusStr='<span class="status-code2">Added items in cart</span>';
								}
	}else{
		$error_msg="Sorry, no such order found!";
	}
}else{
	$error_msg="Sorry, no order details found to display!";
}

?>
</head>

<body>
<?php require_once("include/header.php"); ?>	  
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1>Account</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
             				<li><a href="<?php echo gb_fn_linkCacheHandler('index.php','index.php'); ?>" title="Home">Home</a></li>
             				<li><a href="<?php echo gb_fn_linkCacheHandler('myaccount.php','myaccount.php');?>" title="Home">My account</a></li>
							<li class="active">Activity</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>
	<div class="container">
		<div class="row content">
			<?php if(isset($error_msg)){ ?>
			 			<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $error_msg; ?>
						</div>
					<?php }else if(isset($succ_msg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succ_msg; ?>
  						</div>
					<?php } ?>
			<?php if(isset($orderDetails)){	?>
			<div class="col-md-12 well ">
				<div class="row">
					<div class="col-sm-6 col-xs-6 "> 
						<p><strong>Order ID:</strong> <?php if(isset($orderDetails["full_order_number"]) && $orderDetails["full_order_number"]!=""){ echo $orderDetails["full_order_number"];	} ?></p>
						<p><strong>Created Date:</strong> <?php if(isset($orderDetails["order_date"]) && $orderDetails["order_date"]!=""){ echo date("d M Y", strtotime($orderDetails["order_date"]));	} ?></p>
						<p><strong>Current Status:</strong> <?php echo $statusStr; ?></p>
					</div>
					<div class="col-sm-6 col-xs-6 ">
						<div CLASS="pull-right">
							<!--<p> <strong>Delivery method:</strong> Download     </p>       
            				<p> <a href="#"  title="Continue with the Purchase" class="btn btn-danger">Continue with the Purchase</a></p>
          					-->
          					<?php if(isset($orderDetails["status"]) && $orderDetails["status"]!=2){  ?>
            				<div style="clear:both;" class="continueShoppingButton">
             					<a href="products.htm" class="btn btn-danger" title="Continue Shopping">Continue Shopping</a>
            				</div>
            				<?php } ?>
            			</div>
					</div>
				</div>
				<div class="my-account-pg-hding">Order Summary</div>
					<?php if(isset($orderDetails["order_items"]) && count($orderDetails["order_items"])>0){ 	?>
        			<div class="table-responsive" style="margin-bottom:5px; margin-top:15px;">
						<table class="table table-bordered" style="margin-bottom:0px; background:#fff">
							<thead>
								<tr>
									<th class="col-sm-8 brdr-btm-none" colspan="2">Product</th>
									<th WIDTH="14%" class="col-sm-1 brdr-btm-none">Price</th>
									<th WIDTH="14%" class="col-sm-1 brdr-btm-none">Quantity</th>
									<th WIDTH="14%" class="col-sm-1 brdr-btm-none">Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($orderDetails["order_items"] as $order_item){
										if($dbProductData = $db->Products->findOne(array('publish_on_web' => true, "uuid" => $order_item['uuid_product'])))	{
											$defaultImage=findDefaultImage($dbProductData);
								?>
								<tr>
									<td WIDTH="27%" class="col-sm-3 text-center"><a href="javascript:void(0)" onclick="gb_fn_linkCacheHandlerJS('<?php echo $dbProductData["product_code"].'.html';?>','product.php?code=<?php echo $dbProductData["product_code"];?>')" class="cart-prdt-link"><img src="<?php echo $defaultImage; ?>" CLASS="img-responsive" STYLE="height:170px;"  onerror="this.src='images/default-product-small.png'"></a></td>
									<td WIDTH="69%" class=" col-sm-7"><strong><?php echo ucfirst($dbProductData["ProductName"]); ?></strong><br>
									<?php if(isset($dbProductData["sku"]) && $dbProductData["sku"]!=""){
                							echo "<strong>SKU :</strong> ".$dbProductData["sku"]."<br>";
            						}	?>
									<span class="font-italic"><?php echo "<strong>Description :</strong>".getBriefText($dbProductData["Description"]); ?></span>
									<?php if(isset($dbProductData["stock_available"]) && $dbProductData["stock_available"]>0){
                						echo "<br><strong>Delivery :</strong> 5-10 working days";
            						}	else{
            							echo "<br><strong>Delivery :</strong> 4-6 weeks";
            						}	?>
            						</td>
									<td class="col-sm-1"><?php echo CURRENCY.$order_item["item_rate"]; ?></td>
									<td class="col-sm-1"><?php echo $order_item["item_hours"]; ?></td>
									<td class="col-sm-1"><?php echo CURRENCY.$order_item["item_amount"]; ?></td>
								</tr>
								<?php 	}
								} ?>
							</tbody>
   						 </table>	
	    			</div>
	    			
	    			<div class="table-responsive col-sm-5 col-sm-offset-7 marbot-15" style="background:#1f275e;color:#fff; padding:10px 0; margin-top:10px; ">
						<table class="table" style="margin-bottom:0px;">
						<tbody><tr id="row_subtotal">
							<td class=" col-sm-7 text-left" style="border-top:none;"><strong>Subtotal</strong></td>
							<td class=" col-sm-2 text-left" style="border-top:none;"><?php if(isset($orderDetails["total_due_without_tax"])){ echo CURRENCY.$orderDetails["total_due_without_tax"];	} else{ echo CURRENCY."0"; } ?></td>
							</tr>
							<tr id="row_subtotal">
								<td class=" col-sm-7 text-left"><strong>Tax</strong></td>
								<td class=" col-sm-2 text-left"><?php if(isset($orderDetails["total_tax"])){ echo CURRENCY.$orderDetails["total_tax"];	} else{ echo CURRENCY."0"; }	?></td>
							</tr>
							<tr>
								<td class=" col-sm-7 text-left"><strong>Grand Total</strong></td>
								<td class=" col-sm-2 text-left"><?php if(isset($orderDetails["total_due_with_tax"])){ echo CURRENCY.$orderDetails["total_due_with_tax"];	} else{ echo CURRENCY."0"; } ?></td>
							</tr>
						</tbody></table>
					</div>
					<?php	} 	?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
</body>
</html>
