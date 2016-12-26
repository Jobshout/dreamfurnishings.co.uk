<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<style>
/* stylesheet for */
	html, body, div, span, applet, object, iframe,
	h1, h2, h3, h4, h5, h6, p, blockquote, pre,
	a, abbr, acronym, address, big, cite, code,
	del, dfn, em, img, ins, kbd, q, s, samp,
	small, strike, strong, sub, sup, tt, var,
	b, u, i, center,
	dl, dt, dd, ol, ul, li,
	fieldset, form, label, legend,
	table, caption, tbody, tfoot, thead, tr, th, td,
	article, aside, canvas, details, embed, 
	figure, figcaption, footer, header, hgroup, 
	menu, nav, output, ruby, section, summary,
	time, mark, audio, video {
		padding: 0;
		border: 0;
		margin:0;
		
	}
	/* HTML5 display-role reset for older browsers */
	article, aside, details, figcaption, figure, 
	footer, header, hgroup, menu, nav, section {
		display: block;
	}
	
	/* png fix for IE6 */
	img,div,td,a { behavior: url(iepngfix.htc) }
	
	body{
		font-size:12px;
		font-family:'Open Sans', "Helvetica Neue", Helvetica, Arial, sans-serif;
		color:#222425;
	}


.row{
	clear:both;
	overflow:hidden;
	height:100%;
	margin:0px!important;
}

.col1, .col2, .col3, .col4, .col5, .col6{
	float:left;
}

.floatleft{
	float:left;
}

.floatright{
	float:right;
}
.width385{
	width:381px;
	margin:5px;
	background:#007398;
	color:#FFF;
	min-height:145px;
	
}

.width385 p{
	color:#fff;
	font-size:14px;
	font-weight:normal;
	padding:5px 8px 8px 7px;
	line-height:22px;
}

.mainbcg{
	background:#eef1f3;
	border:1px solid #aeaeae;
	
	width:785px;
	margin:0 auto;
}

.logo{
	padding:10px;
}

h1{
	background:#f49026;
	color:#fff;
	font-size:19px;
	padding:5px 10px;
	font-weight:normal;
	
	
}


h2{
	color:#FFF;
	font-weight:normal;
	font-size:17px;
	padding:5px 7px;
	border-bottom:1px solid #80b9cc;
	margin-bottom:7px;
	text-transform:uppercase;
	
	
}

h3{
	font-size:16px;
	color:#2980b9;
	font-weight:normal;
	font-style:italic;
	padding-left:9px;
	margin-bottom:10px;
	margin-top:7px;

}

.table{
	background:#fff;
	margin:0 auto;	
	width:98%;
	overflow:hidden;
	clear:both;
	margin-bottom:10px;
	font-size:14px;
	color:#333337;
	font-weight:bold;
}

.table table{
	border:1px solid #dddddd;	
	width:100%;
	border-collapse:collapse;
}

.table table td{	
	padding:5px 8px;
	border-collapse:collapse;
	border:1px solid #dddddd;
	
}

.table p{
	font-size:12px;
	font-weight:normal;
	color:#1a1b1b;
	padding-bottom:5px;
	
}

.table h4{
	background:#eef1f3;
	font-size:12px;
	padding:2px 4px;
	margin-bottom:8px;
	font-weight: 600;
	
	
}
.imgpading{
	padding:5px;
}

.total{
	width:50%; background:#152c44; color:#FFF; font-weight:normal; padding:3px 0px; line-height:30px; float:right;
}
.width385 a{
 text-decoration:none;
 color:#FFF;
}

.width385 a:hover{
 text-decoration:underline;
 
}
</style>

<div class="mainbcg">

	<a href="<?php echo SITE_PATH;?>" title="DreamFurnishings"><img src="<?php echo SITE_PATH;?>/images/email_logo.jpg" width="223" class="logo" alt="DreamFurnishings"/></a>
	<h1>Order Confirmation</h1>
	<div class="row">
		<div class="col1 width385"><h2>Order Details </h2>
			<p>Invoice No.: <?php if(isset($orderDetails["full_order_number"]) && $orderDetails["full_order_number"]!=""){ echo $orderDetails["full_order_number"];	} ?><br>
			Order Date: <?php if(isset($orderDetails["order_date"]) && $orderDetails["order_date"]!=""){ echo date("d M Y", strtotime($orderDetails["order_date"]));;	} ?><br>
			Total Amount: <?php if(isset($orderDetails["total_due_with_tax"]) && $orderDetails["total_due_with_tax"]!=""){ echo CURRENCY.$orderDetails["total_due_with_tax"];	} ?></p>
		</div>
		<div class="col1 width385"><h2>BILLING INFORMATION</h2>
			<p><?php if(isset($userLoggedIn["First name"]) && $userLoggedIn["First name"]!=""){ echo $userLoggedIn["First name"];	} ?>&nbsp;<?php if(isset($userLoggedIn["Surname"]) && $userLoggedIn["Surname"]!=""){ echo $userLoggedIn["Surname"];	} ?><br>
			<?php if(isset($userLoggedIn["address_line_1"]) && $userLoggedIn["address_line_1"]!=""){ echo $userLoggedIn["address_line_1"];	} ?>
								<?php if(isset($userLoggedIn["address_line_2"]) && $userLoggedIn["address_line_2"]!=""){ echo ", ".$userLoggedIn["address_line_2"];	} ?>
								<?php if(isset($userLoggedIn["address_line_3"]) && $userLoggedIn["address_line_3"]!=""){ echo ", ".$userLoggedIn["address_line_3"];	} ?>
								<?php if(isset($userLoggedIn["county_or_state"]) && $userLoggedIn["county_or_state"]!=""){ echo ", ".$userLoggedIn["county_or_state"];	} ?>
								<?php if(isset($userLoggedIn["country"]) && $userLoggedIn["country"]!=""){ echo ", ".$userLoggedIn["country"];	} ?>
								<?php if(isset($userLoggedIn["post_zip_code"]) && $userLoggedIn["post_zip_code"]!=""){ echo ", ".$userLoggedIn["post_zip_code"];	} ?><br>
			Telephone: <?php if(isset($userLoggedIn["Mobile"]) && $userLoggedIn["Mobile"]!=""){ echo $userLoggedIn["Mobile"];	} ?><br>
			E-mail: <a href="mailto:<?php if(isset($userLoggedIn["Email"]) && $userLoggedIn["Email"]!=""){ echo $userLoggedIn["Email"];	} ?>"><?php if(isset($userLoggedIn["Email"]) && $userLoggedIn["Email"]!=""){ echo $userLoggedIn["Email"];	} ?></p>
		</div>
	</div>
	
	<?php if(isset($orderDetails["order_items"]) && count($orderDetails["order_items"])>0){ 	?>
	<div class="table" >
		<table  border="0" cellspacing="0" cellpadding="0" >
			<tr><td>&nbsp;</td><td>Description</td><td>Price</td><td>Quantity</td><td>Subtotal</td></tr>
			<?php foreach($orderDetails["order_items"] as $order_item){
										if($dbProductData = $db->Products->findOne(array('publish_on_web' => true, "uuid" => $order_item['uuid_product'])))	{
											$defaultImage=findDefaultImage($dbProductData);
											$productLinkStr=gb_fn_linkCacheHandler('product-'.$dbProductData["product_code"].'.html','product.php?code='.$dbProductData["product_code"]);
								?>
								<tr>
									<td><a href="<?php echo SITE_PATH.'/'.$productLinkStr;?>"><img src="<?php echo SITE_PATH.$defaultImage; ?>" CLASS="img-responsive" STYLE="height:170px;"  onerror="this.src='<?php echo SITE_PATH; ?>/images/default-product-small.png'"></a></td>
									<td><strong><?php echo ucfirst($dbProductData["ProductName"]); ?></strong><br>
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
									<td><?php echo CURRENCY.$order_item["item_rate"]; ?></td>
									<td><?php echo $order_item["item_hours"]; ?></td>
									<td><?php echo CURRENCY.$order_item["item_amount"]; ?></td>
								</tr>
								<?php 	}
								} ?>

		<tr>
			<td colspan="5">
			<div  class="total">
				<div class="row"  style="padding:0 7px;">
					<div><div class="col1">Subtotal</div>
					<div class="col2 floatright"><?php if(isset($orderDetails["total_due_without_tax"])){ echo CURRENCY.$orderDetails["total_due_without_tax"];	} ?></div></div>
				</div> 
				<div class="row" style="border-top:1px solid #FFFFFF; padding:0 7px;"  >
					<div class="col3">Tax</div>
					<div class="col4 floatright"><?php if(isset($orderDetails["total_tax"])){ echo CURRENCY.$orderDetails["total_tax"];	} ?></div>
				</div>
				<div class="row" style="border-top:1px solid #FFFFFF; padding:0 7px;"  >
					<div class="col3">Grand Total</div>
					<div class="col4 floatright"><?php if(isset($orderDetails["total_due_with_tax"])){ echo CURRENCY.$orderDetails["total_due_with_tax"];	} ?></div>
				</div>
			</div>
			</td>
		</tr>
		</table>
	</div>
    <?php } ?>
    </td>
  </tr>
  
</table>
</div>

</div>
</body>
</html>