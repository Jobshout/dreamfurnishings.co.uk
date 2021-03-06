<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");

require_once("login_submit_request.php");

$updateBillingAddress=true;
require_once("save_contact.php");

?>
<link rel="stylesheet" type="text/css" href="css/accordion.css" />
</head>
<body>
<?php require_once("include/header.php"); ?>
<section>
	<div class="headingbcg " >
		<div class="container">
        	<div class="row">
          		<div class="col-md-8 col-sm-8">
            		<h1>Checkout</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
             				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">Checkout</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>
	<div class="container">
		<div class="row content">
			<div CLASS="col-md-8">
				<span class="displayMessageClass"></span>
				<?php if(isset($_GET['error'])){ ?>
						<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $_GET['error']; ?>
						</div>
				<?php } else if(isset($err_msg)){ ?>
			 			<div class="alert alert-danger alert-dismissable">
  							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $err_msg; ?>
						</div>
				<?php }else if(isset($succ_msg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succ_msg; ?>
  						</div>
				<?php } ?>
				<div class="accordion">
            		<dl>
            			<?php if(!$isUserSignedInBool){ ?>
             			<dt><a class="accordionTitleActive" href="javascript:void(0)">Login</a></dt>
              			<dd class="accordionItem animateIn" STYLE="border-bottom:1px solid #ddd;">
                			<form role="form" name="login" id="login" method="post" action="">
                				<div class="panel-body">
                        			<p>Returning Customer</p>
                        			<input type="hidden" id="action" name="action" value="login">
                        			<input id="redirect" name="redirect" value="checkout" type="hidden">
                        			<div CLASS="form-group"><input placeholder="Email" id="email_address" name="email_address" type="text" CLASS="form-control"></div>
                          			<div CLASS="form-group"><input placeholder="Password" type="password" id="password" name="password" CLASS="form-control"></div>
                          			<input type="submit" name="sign_in" class="btn btn-danger" value="Sign In">
                       				<span class="aa-lost-password"><a href="login.htm?action=requestnewpassword" class="btn btn-primary">Lost your password?</a></span>
                       				<span class="aa-lost-password" STYLE="float:right;">Don't have an account yet? <a href="register.htm" CLASS="btn btn-primary">Create an account</a></span>
                        		</div>
                        	</form>
						</dd>
						<?php } else { ?>
              			<dt><a href="javascript:void(0)" class="<?php if($isUserSignedInBool){ echo "accordionTitleActive"; }	?> ">Billing Details </a></dt>
              			<dd class="accordionItem <?php if(!$isUserSignedInBool){ echo "animateOut accordionItemCollapsed"; }else{ echo "animateIn";	} ?>"  STYLE="border-bottom:1px solid #ddd;"> 
             				<form role="form" name="myaccount" id="myaccount" method="post" ><div class="panel-body">
                          		<div class="row">
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="first_name" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['First name'])){ echo $userLoggedIn['First name']; } ?>" placeholder="First Name*" id="first_name" class="form-control inputControl" type="text">
        									<input name="uuid" value="<?php if(isset($userLoggedIn) && $userLoggedIn['uuid']!=""){ echo $userLoggedIn['uuid']; } ?>" id="uuid" class="form-control inputControl" type="hidden">
                                		</div>                             
                            		</div>
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="last_name" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Surname'])){ echo $userLoggedIn['Surname']; } ?>" placeholder="Last Name*" id="last_name" class="form-control inputControl" type="text">
                                		</div>
                            		</div>
                          		</div> 
                          		  
                          		<div class="row">
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="email_address" readonly value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Email'])){ echo $userLoggedIn['Email']; } ?>" placeholder="Email Address*" id="email_address" class="form-control inputControl"  type="text">
                                		</div>                             
                            		</div>
                            		<div class="col-md-6">
                              			<div class="form-group">
                                			<input name="telephone" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['Mobile'])){ echo $userLoggedIn['Mobile']; } ?>" placeholder="Mobile*" id="telephone" class="form-control inputControl" type="text">
                              			</div>
                            		</div>
                          		</div> 
                          		<div class="row">
                            		<div class="col-md-12">
                              			<div class="form-group">
                                			<textarea cols="8" rows="3" CLASS="form-control" placeholder="Address 1" id="address_line_1" name="address_line_1"><?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_1'])){ echo $userLoggedIn['address_line_1']; } ?></textarea>
                              			</div>                             
                            		</div>                            
                          		</div>   
                          		<div class="row">
                            		<div class="col-md-12">
                              			<div class="form-group">
                              				<select class="form-control" id="country" name="country">
                    							<option value="">--Select Country--</option>
												<?php $country_table = $db->countries->find(array("status"=>1));
													if($country_table->count()>0){
														foreach($country_table as $country){
															$selectedStr="";
															if(isset($userLoggedIn['country']) && $userLoggedIn['country']==$country['name']){ 
																$selectedStr="selected";
															}
															echo '<option value="'.$country['name'].'" '.$selectedStr.'>'.$country['name'].'</option>';
														}
													}
												?>
										</select>
                               		</div>                             
                            	</div>                            
                          	</div>
                          	<div class="row">
                            	<div class="col-md-6">
                              		<div class="form-group">
                                		<input name="address_line_2" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_2'])){ echo $userLoggedIn['address_line_2']; } ?>" placeholder="Address 2" id="address_line_2" class="form-control inputControl" type="text">
                              		</div>                             
                            	</div>
                            	<div class="col-md-6">
                              		<div class="form-group">
                              			<input name="city" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['address_line_3'])){ echo $userLoggedIn['address_line_3']; } ?>" placeholder="City*" id="city" class="form-control inputControl" type="text">
                               		</div>
                            	</div>
                          	</div>   
                          	<div class="row">
                            	<div class="col-md-6">
                              		<div class="form-group">
                                		<input name="state" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['county_or_state'])){ echo $userLoggedIn['county_or_state']; } ?>" placeholder="State/County*" id="state" class="form-control inputControl" type="text">
                              		</div>                             
                            	</div>
                            	<div class="col-md-6">
                              	<div class="form-group">
                              		<input name="postcode" value="<?php if(isset($userLoggedIn) && isset($userLoggedIn['post_zip_code'])){ echo $userLoggedIn['post_zip_code']; } ?>" placeholder="Postcode / Zip*" id="postcode" class="form-control inputControl" type="text">
                                </div>
                            </div>
                          </div>
                          
                          	<h2>Delivery Address</h2>
                          		<label class="checkbox" style="margin-left: 23px;color:#999;">
									<input name="same_delivery_address" value="true" id="same_delivery_address" type="checkbox" <?php if(isset($userLoggedIn) && isset($userLoggedIn['same_delivery_address']) && $userLoggedIn['same_delivery_address']==true){ echo 'checked'; } ?> >Same as billing address
								</label>
								<?php $deliveryAddressStr= isset($userLoggedIn['delivery_address']) ? $userLoggedIn['delivery_address'] : '';	?>
                          		<div class="row">
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="d_first_name" value="<?php if(isset($deliveryAddressStr['first_name'])){ echo $deliveryAddressStr['first_name']; } ?>" placeholder="First Name*" id="d_first_name" class="form-control inputControl" type="text">
        								</div>                             
                            		</div>
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="d_last_name" value="<?php if(isset($deliveryAddressStr['last_name'])){ echo $deliveryAddressStr['last_name']; } ?>" placeholder="Last Name*" id="d_last_name" class="form-control inputControl" type="text">
                                		</div>
                            		</div>
                          		</div> 
                          		  
                          		<div class="row">
                            		<div class="col-md-6">
                              			<div class="form-group">
                              				<input name="d_email_address" value="<?php if(isset($deliveryAddressStr['Email'])){ echo $deliveryAddressStr['Email']; } ?>" placeholder="Email Address*" id="d_email_address" class="form-control inputControl"  type="text">
                                		</div>                             
                            		</div>
                            		<div class="col-md-6">
                              			<div class="form-group">
                                			<input name="d_telephone" value="<?php if(isset($deliveryAddressStr['Mobile'])){ echo $deliveryAddressStr['Mobile']; } ?>" placeholder="Mobile*" id="d_telephone" class="form-control inputControl" type="text">
                              			</div>
                            		</div>
                          		</div> 
                          		<div class="row">
                            		<div class="col-md-12">
                              			<div class="form-group">
                                			<textarea cols="8" rows="3" CLASS="form-control" placeholder="Address 1" id="d_address_line_1" name="d_address_line_1"><?php if(isset($deliveryAddressStr['address_line_1'])){ echo $deliveryAddressStr['address_line_1']; } ?></textarea>
                              			</div>                             
                            		</div>                            
                          		</div>   
                          		<div class="row">
                            		<div class="col-md-12">
                              			<div class="form-group">
                              				<select class="form-control" id="d_country" name="d_country">
                    							<option value="">--Select Country--</option>
												<?php $country_table = $db->countries->find(array("status"=>1));
													if($country_table->count()>0){
														foreach($country_table as $country){
															$selectedStr="";
															if(isset($deliveryAddressStr['country']) && $deliveryAddressStr['country']==$country['name']){ 
																$selectedStr="selected";
															}
															echo '<option value="'.$country['name'].'" '.$selectedStr.'>'.$country['name'].'</option>';
														}
													}
												?>
										</select>
                               		</div>                             
                            	</div>                            
                          	</div>
                          	<div class="row">
                            	<div class="col-md-6">
                              		<div class="form-group">
                                		<input name="d_address_line_2" value="<?php if(isset($deliveryAddressStr['address_line_2'])){ echo $deliveryAddressStr['address_line_2']; } ?>" placeholder="Address 2" id="d_address_line_2" class="form-control inputControl" type="text">
                              		</div>                             
                            	</div>
                            	<div class="col-md-6">
                              		<div class="form-group">
                              			<input name="d_city" value="<?php if(isset($deliveryAddressStr['address_line_3'])){ echo $deliveryAddressStr['address_line_3']; } ?>" placeholder="City*" id="d_city" class="form-control inputControl" type="text">
                               		</div>
                            	</div>
                          	</div>   
                          	<div class="row">
                            	<div class="col-md-6">
                              		<div class="form-group">
                                		<input name="d_state" value="<?php if(isset($deliveryAddressStr['county_or_state'])){ echo $deliveryAddressStr['county_or_state']; } ?>" placeholder="State/County*" id="d_state" class="form-control inputControl" type="text">
                              		</div>                             
                            	</div>
                            	<div class="col-md-6">
                              		<div class="form-group">
                              			<input name="d_postcode" value="<?php if(isset($deliveryAddressStr['post_zip_code'])){ echo $deliveryAddressStr['post_zip_code']; } ?>" placeholder="Postcode / Zip*" id="d_postcode" class="form-control inputControl" type="text">
                                	</div>
                           		</div>
                          	</div>
                          	<div class="row"><div class="col-md-12"><input type="submit" value="Continue >" CLASS="btn btn-danger" name="submit" style="float:right"></div></div>                                    
                        </div></form>
						</dd>
						<?php } ?>
            	</dl>
          	</div>
		</div>
		<div CLASS="col-md-4">
			<h3 STYLE="color:#e68e03; padding-top:0x; margin-top:0px;">Order Summary</h3>
				<table class="table table-responsive table-striped table-bordered" >
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>Total</th>
                        </tr>
                      </thead>
                      <tbody id="cartTable">
                       
                      </tbody>
                      <tfoot >
                        <tr>
                          <th>Subtotal</th>
                          <td><?php echo CURRENCY;?><span id="subTotalHere">0</span></td>
                        </tr>
                         <tr <?php if(HIDETAX){ ?>style="display:none;"<?php } ?> >
                          <th>Tax</th>
                          <td><?php echo CURRENCY;?><span id="totalTaxHere">0</span></td>
                        </tr>
                         <tr>
                          <th>Total</th>
                          <td><?php echo CURRENCY;?><span id="grandTotalHere">0</span></td>
                        </tr>
                      </tfoot>
                </table>
                <input name="tax_rate" value="<?php if($isUserSignedInBool && isset($session_values['tax_rate'])) { echo $session_values['tax_rate']; } else { echo '0'; } ?>" id="tax_rate" type="hidden">
            </div>
		</div>
	</div>
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
<script src="js/jquery.validate.min.js"></script>
<script>
$(function () {
	load_data();	
	$("#same_delivery_address").click(function() {
    	if ($(this).is(':checked')) {
      		$("#d_first_name").val($("#first_name").val());
      		$("#d_last_name").val($("#last_name").val());
      		$("#d_email_address").val($("#email_address").val());
      		$("#d_telephone").val($("#telephone").val());
      		$("#d_address_line_1").val($("#address_line_1").val());
      		$("#d_country").val($("#country").val());
      		$("#d_address_line_2").val($("#address_line_2").val());
      		$("#d_city").val($("#city").val());
      		$("#d_state").val($("#state").val());
      		$("#d_postcode").val($("#postcode").val());
    	} else{
    		$("#d_first_name").val('');
    		$("#d_last_name").val('');
    		$("#d_email_address").val('');
    		$("#d_telephone").val('');
    		$("#d_address_line_1").val('');
    		$("#d_country").val('');
    		$("#d_address_line_2").val('');
    		$("#d_city").val('');
    		$("#d_state").val('');
    		$("#d_postcode").val('');
    	}
    });
});

var xhr;
function load_data(){
	var jsonRow="return_preferences_json.htm?action=cart";
	if(xhr) xhr.abort();
	xhr=$.getJSON(jsonRow,function(result){
		if(result.error){
			//$('.cartTable').before('<div class="alert alert-danger" role="alert">'+result.error+'</div>');
		}else{
			var htmlStr="";		
			$.each(result.aaData, function(i,item){
				htmlStr+='<tr id="'+item.id+'" >';
				
            	htmlStr+='<td>'+item.name+'';
            	var quantity = 1;
            	if(item.Quantity){
            		quantity = parseInt(item.Quantity);
            	}
              	htmlStr+=' <strong> x  '+quantity+'</td>';
              	var subtotalFloat=parseFloat(item.price)*quantity;
              	htmlStr+='<td>'+item.currency+subtotalFloat+'<input type="hidden" value="'+subtotalFloat+'" class="subtotalClass"></td>';
              	htmlStr+='</tr>';
            }); 
			
			$('#cartTable').html(htmlStr);
			evaluateGrandTotal();
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
	
	if(taxRateNum!=0){
		total_tax =subtotal*taxRateNum/100;
		total_tax=total_tax.toFixed(2);
	}
	
	grandTotal = parseFloat(subtotal)+parseFloat(total_tax);
	grandTotal=grandTotal.toFixed(2);  
	$("#grandTotalHere").html(grandTotal);
	$("#totalTaxHere").html(total_tax);
}
$(function () {  	
    	$( "#login" ).validate( {
        	errorElement: "em",
			rules: {
				email_address : { required :true, email: true},
				password : { required: true }
			},
 			messages: {
    			email_address: {
      				required: "Please enter your email address",
      				email: "Please enter a valid email address"
    			},
    			password: {
      				required: "Please enter password"
    			}
  			}
		});
		$( "#myaccount" ).validate( {
        	errorElement: "em",
			rules: {
				first_name : { required: true },
				last_name : { required: true },
				telephone : { required: true },
				address_line_1 : { required: true },
				country : { required: true },
				city : { required: true },
				state : { required: true },
				postcode : { required: true },
				email_address : { required :true, email: true},
				d_first_name : { required: true },
				d_last_name : { required: true },
				d_telephone : { required: true },
				d_address_line_1 : { required: true },
				d_country : { required: true },
				d_city : { required: true },
				d_state : { required: true },
				d_postcode : { required: true },
				d_email_address : { required :true, email: true}
			}
		});
    });
</script>


</body>
</html>