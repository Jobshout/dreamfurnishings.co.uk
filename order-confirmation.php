<?php
require_once("include/config_inc.php");
require_once("include/main_header.php");
require_once ('PaymentFormHelper.php');
require_once('include/class.phpmailer.php');
if(isset($isUserSignedInBool) && $isUserSignedInBool==true){
	if(isset($session_values['checkout_state']) && $session_values['checkout_state']==1){
		if(isset($session_values['transaction_uuid']) && $session_values['transaction_uuid']!=""){
			if($orderDetails = $db->orders->findOne(array("uuid" =>  $session_values['transaction_uuid'],  "order_items" => array('$exists' => true)))){
			
			}else{
				header("Location: cart.php?error=Add some items in your cart!"); exit;
			}
		}else{
			header("Location: cart.php?error=Add some items in your cart!"); exit;
		}
	}else{
		header("Location: checkout.php?error=Confirm billing address before payment!"); exit;
	}
}else{
	header("Location: login.php?redirect=confirm_billing"); exit;
}

if(!empty($_POST['StatusCode'])){
	if($_POST['StatusCode']==0){
		if($db->orders->update(array("uuid" => $session_values['transaction_uuid']), array('$set' => array("status"=>2, "approved_date"=>date("Y-m-d"))))){
			$orderDetails = $db->orders->findOne(array("uuid" =>  $session_values['transaction_uuid'],  "order_items" => array('$exists' => true)));
			
			// clear all options
			$session_update= array("checkout_state"=>-1, "subtotal"=> 0, "total_tax" =>  0, "tax_rate"=> 0, "tax_code"=> 0, "total"=> 0, "discount"=>0, "transaction_uuid"=>"", "cart"=>array()); // checkout_state=1 confirmation of address, checkout_state=0 items are in cart, -1 for clear 
			$db->session->update(array("_id" => $session_values['_id']), array('$set' => $session_update));
			
			//confirmation email here
			$subject="Order Confirmation Mail";
			$HTMLEmailBodyTxt='';
			ob_start();
			include("confirmation_email.php");
			$HTMLEmailBodyTxt=ob_get_contents();
			ob_end_clean();
						
			$userEmailHtml = $HTMLEmailBodyTxt;					  
			$adminEmailHtml = $HTMLEmailBodyTxt;
			$adminEmailHtml .= "<div><strong>IP:</strong> <em>".__ipAddress()."</em></div>";
			
			require_once("include/mailer-details.php");
			// send email to user
			if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
						try {
							$mail->AddReplyTo(ADMIN_EMAIL,SITE_NAME);
							$mail->AddAddress($userLoggedIn["Email"]);
							$mail->SetFrom(ADMIN_EMAIL,SITE_NAME);		
			
							$mail->Subject = $subject;
			
							$mail->MsgHTML($userEmailHtml);
							$mail->Send();
							$mail->ClearAddresses();
						}catch (phpmailerException $e) {
							save_email_queue($userLoggedIn["Email"], ADMIN_EMAIL, $subject, $userEmailHtml);	// sendto, sendfrom, subject and content
						}
						catch (Exception $e) {
							save_email_queue($userLoggedIn["Email"], ADMIN_EMAIL, $subject, $userEmailHtml);	// sendto, sendfrom, subject and content
						}
				}else{
					save_email_queue($userLoggedIn["Email"], ADMIN_EMAIL, $subject, $userEmailHtml);	// sendto, sendfrom, subject and content
				}		
					
				//send this email to admin	
				if(isset($mailerDetailsAvailableInDbFlag) && $mailerDetailsAvailableInDbFlag==true){
						try {
							$mail->AddReplyTo($userLoggedIn["Email"]);
							$mail->AddAddress(ADMIN_EMAIL,SITE_NAME);
							$mail->SetFrom($userLoggedIn["Email"]);		
			
							$mail->Subject = $subject;
			
							$mail->MsgHTML($adminEmailHtml);
							$mail->Send();
							$mail->ClearAddresses();
						}catch (phpmailerException $e) {
							save_email_queue(ADMIN_EMAIL, $userLoggedIn["Email"], $subject, $adminEmailHtml);	// sendto, sendfrom, subject and content
						}
						catch (Exception $e) {
							save_email_queue(ADMIN_EMAIL, $userLoggedIn["Email"], $subject, $adminEmailHtml);	// sendto, sendfrom, subject and content
						}
				}else{
					save_email_queue(ADMIN_EMAIL, $userLoggedIn["Email"], $subject, $adminEmailHtml);	// sendto, sendfrom, subject and content
				}
				
			$succ_msg="Thank you, your order has been placed successfully.";
		}else{
			header("Location: cart.php?error=Add some items in your cart!"); exit;
		}
	}else{
		$err_msg="Sorry, ".$_POST['Message'];
	}	
}

$showPurchaseBtnBool=false;
if(isset($orderDetails["status"]) && $orderDetails["status"]<=1){

/*merchants details*/
$tokensQry= $db->Tokens->find(array("code" => array('$in' => array('payment-sense-merchantid','payment-sense-password','payment-sense-securekey'))));
if($tokensQry->count()>0){
	foreach($tokensQry as $token){	
		if(isset($token["contentTxt"]) && $token["contentTxt"]!=""){
			
			if(isset($token["code"]) && $token["code"]=="payment-sense-merchantid"){
				$MerchantID=$token["contentTxt"];
			}elseif(isset($token["code"]) && $token["code"]=="payment-sense-password"){
				$PaymentSensePwd=$token["contentTxt"];
			}elseif(isset($token["code"]) && $token["code"]=="payment-sense-securekey"){
				$PreSharedKey=$token["contentTxt"];
			}
		}
	}
}
if(isset($MerchantID) && isset($PaymentSensePwd) && isset($PreSharedKey) && $MerchantID!="" && $PaymentSensePwd!="" && $PreSharedKey!=""){
$showPurchaseBtnBool=true; //set true because merchant login details are given

// This method MUST match the hash method set for the merchant in the MMS
$HashMethod = 'SHA1';
	
// The domain ONLY for the hosted payment form - Do Not Change
$PaymentProcessorDomain = 'paymentsensegateway.com';

	/* determines how the transaction result will be delivered back to this site:
	* "POST" - only use if this site has an SSL certificate. Best method to use if you do have an SSL
	* "SERVER" - best method with no SSL - don't use if this site requires to maintain 
	*			  cookie-based session to access its order object)
	* "SERVER_PULL" - only use if no SSL and site also requires cookie-based session to access 
	*			  	   its order object
	*/			  	   
	
	$ResultDeliveryMethod = 'POST';
	
	//Hosted Payment Form Settings	: mandatory
	$szEmailAddressEditable = PaymentFormHelper::boolToString(true);
	$szPhoneNumberEditable = PaymentFormHelper::boolToString(true);
	$szCV2Mandatory = PaymentFormHelper::boolToString(true);
	$szAddress1Mandatory = PaymentFormHelper::boolToString(true);
	$szCityMandatory = PaymentFormHelper::boolToString(true);
	$szPostCodeMandatory = PaymentFormHelper::boolToString(true);
	$szStateMandatory = PaymentFormHelper::boolToString(true);
	$szCountryMandatory = PaymentFormHelper::boolToString(true);
	
	//misc return values
	$szEchoCardType = PaymentFormHelper::boolToString(true); //set to true by default
	
	//Security Override Policys
	$AVSOverridePolicy = '';
	$CV2OverridePolicy = '';
	$ThreeDSecureOverridePolicy = PaymentFormHelper::boolToString(false);
	
	$FormAction = 'https://mms.'.$PaymentProcessorDomain.'/Pages/PublicPages/PaymentForm.aspx';
	

	// the amount in *minor* currency (i.e. £10.00 passed as "1000")
	$szAmount = $orderDetails["total_due_with_tax"] * 100;
	// the currency	- ISO 4217 3-digit numeric (e.g. GBP = 826)
	$szCurrencyCode = 826;
	// order ID
	$szOrderID = $orderDetails['full_order_number'];
	// the transaction type - can be SALE or PREAUTH
	$szTransactionType = "SALE";
	// the GMT/UTC relative date/time for the transaction (MUST either be in GMT/UTC 
	// or MUST include the correct timezone offset)
	$szTransactionDateTime = date('Y-m-d H:i:s P');
	// order description
	$szOrderDescription = 'Invoice no :'.$orderDetails['full_order_number'];
	// these variables allow the payment form to be "seeded" with initial values
	$szCustomerName = $userLoggedIn["First name"]." ".$userLoggedIn["Surname"];
	$szAddress1 = $userLoggedIn["address_line_1"];	// Important for AVS Check 
	$szAddress2 = $userLoggedIn['address_line_2'];
	$szAddress3 = '';
	$szAddress4 = '';
	$szCity = $userLoggedIn['address_line_3'];
	$szState = $userLoggedIn['county_or_state'];
	$szPostCode = $userLoggedIn['post_zip_code']; // Important for AVS Check
	// the country code - ISO 3166-1  3-digit numeric (e.g. UK = 826)
	$szCountryCode = fetchCountryCode($userLoggedIn['country']);
		
	//Email Address
    $_SESSION['email'] = $userLoggedIn['Email'];//Paymentsense Amendment
	$szEmailAddress = $userLoggedIn['Email'];
	//Phone Number
	$_SESSION['phone_number'] = $userLoggedIn['Mobile'];//Paymentsense Amendment
	$szPhoneNumber = $userLoggedIn['Mobile'];
	
	// the URL on this system that the payment form will push the results to (only applicable for 
	// ResultDeliveryMethod = "SERVER")
	if ($ResultDeliveryMethod != "SERVER")
	{
		$szServerResultURL = '';
	}
	else
	{
		$szServerResultURL = PaymentFormHelper::getSiteSecureBaseURL().'ReceiveTransactionResult.php';
	}
	// set this to true if you want the hosted payment form to display the transaction result
	// to the customer (only applicable for ResultDeliveryMethod = "SERVER")
	if ($ResultDeliveryMethod != 'SERVER')
	{
		$szPaymentFormDisplaysResult = '';
	}
	else
	{
		$szPaymentFormDisplaysResult = PaymentFormHelper::boolToString(false);
	}
	// the callback URL on this site that will display the transaction result to the customer
	// (always required unless ResultDeliveryMethod = "SERVER" and PaymentFormDisplaysResult = "true")
	if ($ResultDeliveryMethod == 'SERVER' && PaymentFormHelper::stringToBool($szPaymentFormDisplaysResult) == false)
	{
		$szCallbackURL = PaymentFormHelper::getSiteSecureBaseURL().'order-confirmation.php';
	}
	else
	{
		$szCallbackURL = PaymentFormHelper::getSiteSecureBaseURL().'order-confirmation.php'; 
	}

	// get the string to be hashed
	$szStringToHash = PaymentFormHelper::generateStringToHash($MerchantID,
			        										  $PaymentSensePwd,
			        										  $szAmount,
															  $szCurrencyCode,
															  $szEchoCardType,
															  $szOrderID,
															  $szTransactionType,
															  $szTransactionDateTime,
															  $szCallbackURL,
															  $szOrderDescription,
															  $szCustomerName,
															  $szAddress1,
															  $szAddress2,
															  $szAddress3,
															  $szAddress4,
															  $szCity,
															  $szState,
															  $szPostCode,
															  $szCountryCode,
															  $szEmailAddress,
															  $szPhoneNumber,
															  $szEmailAddressEditable,
															  $szPhoneNumberEditable,
															  $szCV2Mandatory,
															  $szAddress1Mandatory,
															  $szCityMandatory,
															  $szPostCodeMandatory,
															  $szStateMandatory,
															  $szCountryMandatory,
															  $ResultDeliveryMethod,
															  $szServerResultURL,
															  $szPaymentFormDisplaysResult,
			         		                                  $PreSharedKey,
			         		                                  $HashMethod);

	// pass this string into the hash function to create the hash digest
	$szHashDigest = PaymentFormHelper::calculateHashDigest($szStringToHash, $PreSharedKey, $HashMethod);
}
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
            		<h1>Order Confirmation</h1>
          		</div>
          		<div class="col-md-4 col-sm-4 ">
		  			<div class="text-right bred-crumb-xs clearfix">
            			<ol class="breadcrumb ">
              				<li><a onclick="gb_fn_linkCacheHandlerJS('index.php','index.php')" href="javascript:void(0)" title="Home">Home</a></li>
							<li class="active">Order Confirmation</li>
            			</ol>
					</div>
          		</div>
        	</div>
      	</div>
	</div>
	<div class="container">
		<div class="row content">
			<?php if(isset($err_msg)){ ?>
			 			<div class="alert alert-danger ">
  							<i class="glyphicon glyphicon-exclamation-sign"></i> <?php echo $err_msg; ?>
						</div>
					<?php }else if(isset($succ_msg)){ ?>
						<div class="alert alert-success ">    
  							<i class=" glyphicon glyphicon-ok-circle Added to your Cart"></i> <?php echo $succ_msg; ?>
  						</div>
					<?php } ?>
			<div class="col-md-12 well ">
					
				<?php if(isset($orderDetails)){	?>
					
					<div class="row mrgn-btm15">
            			<div class="col-sm-6">
							<h4 CLASS="heding"><span> Order Detail </span></h4>
							<div class="borderd-btm-row">
								<div class="col-xs-4 col-sm-3 pding-none"> Invoice No:  </div>
								<div class="col-xs-8 col-sm-9"> <strong class="big-hding"> <?php if(isset($orderDetails["full_order_number"]) && $orderDetails["full_order_number"]!=""){ echo $orderDetails["full_order_number"];	} ?></strong></div>
							</div>
							<div class="borderd-btm-row">
								<div class="col-xs-4 col-sm-3 pding-none">Order Date:</div>
								<div class="col-xs-8 col-sm-9"><?php if(isset($orderDetails["order_date"]) && $orderDetails["order_date"]!=""){ echo date("d M Y", strtotime($orderDetails["order_date"]));	} ?></div>
							</div>
							<div class="borderd-btm-row">
								<div class="col-xs-4 col-sm-3 pding-none">Total Amount:</div>
								<div class="col-xs-8 col-sm-9"><strong class="big-hding totalAmtClass"><?php if(isset($orderDetails["total_due_with_tax"]) && $orderDetails["total_due_with_tax"]!=""){ echo CURRENCY.$orderDetails["total_due_with_tax"];	} ?></strong></div>
							</div>
						</div>
						<div class="col-sm-6">
							<h4 CLASS="heding"><span>Billing Information</span></h4>
							<div style="padding:0 5px;">
								<p class="big-hding" style="padding:0px;"><STRONG>Name:</STRONG> <?php if(isset($userLoggedIn["First name"]) && $userLoggedIn["First name"]!=""){ echo $userLoggedIn["First name"];	} ?>&nbsp;<?php if(isset($userLoggedIn["Surname"]) && $userLoggedIn["Surname"]!=""){ echo $userLoggedIn["Surname"];	} ?></p>
								<P><SPAN CLASS="big-hding" STYLE="padding:0px;"><STRONG>Address:</STRONG> <?php if(isset($userLoggedIn["address_line_1"]) && $userLoggedIn["address_line_1"]!=""){ echo $userLoggedIn["address_line_1"];	} ?></SPAN>
								<?php if(isset($userLoggedIn["address_line_2"]) && $userLoggedIn["address_line_2"]!=""){ echo ", ".$userLoggedIn["address_line_2"];	} ?>
								<?php if(isset($userLoggedIn["address_line_3"]) && $userLoggedIn["address_line_3"]!=""){ echo ", ".$userLoggedIn["address_line_3"];	} ?>
								<?php if(isset($userLoggedIn["county_or_state"]) && $userLoggedIn["county_or_state"]!=""){ echo ", ".$userLoggedIn["county_or_state"];	} ?>
								<?php if(isset($userLoggedIn["country"]) && $userLoggedIn["country"]!=""){ echo ", ".$userLoggedIn["country"];	} ?>
								<?php if(isset($userLoggedIn["post_zip_code"]) && $userLoggedIn["post_zip_code"]!=""){ echo ", ".$userLoggedIn["post_zip_code"];	} ?>
								
								</P>
								<P><STRONG>Mobile:</STRONG> <?php if(isset($userLoggedIn["Mobile"]) && $userLoggedIn["Mobile"]!=""){ echo $userLoggedIn["Mobile"];	} ?></P>
								<P><STRONG>Email:</STRONG> <a href="mailto:<?php if(isset($userLoggedIn["Email"]) && $userLoggedIn["Email"]!=""){ echo $userLoggedIn["Email"];	} ?>"><?php if(isset($userLoggedIn["Email"]) && $userLoggedIn["Email"]!=""){ echo $userLoggedIn["Email"];	} ?></a></P>
							</div>  
						</div>
        			</div>
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
									<td WIDTH="27%" class="col-sm-3 text-center"><a href="javascript:void(0)" onclick="gb_fn_linkCacheHandlerJS('<?php echo 'product-'.$dbProductData["product_code"].'.html';?>','product.php?code=<?php echo $dbProductData["product_code"];?>')" class="cart-prdt-link"><img src="<?php echo $defaultImage; ?>" CLASS="img-responsive" STYLE="height:170px;"  onerror="this.src='images/default-product-small.png'"></a></td>
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
	    			<?php	} 	?>
					<div class="table-responsive col-sm-5 col-sm-offset-7 marbot-15" style="background:#1f275e;color:#fff; padding:10px 0; margin-top:10px; ">
						<table class="table" style="margin-bottom:0px;">
							<tbody>
								<tr id="row_subtotal">
									<td class=" col-sm-7 text-left" style="border-top:none;"><strong>Subtotal</strong></td>
									<td class=" col-sm-2 text-left" style="border-top:none;"><?php if(isset($orderDetails["total_due_without_tax"])){ echo CURRENCY.$orderDetails["total_due_without_tax"];	} ?></td>
								</tr>
								<tr id="row_subtotal">
									<td class=" col-sm-7 text-left"><strong>Tax</strong></td>
									<td class=" col-sm-2 text-left"><?php if(isset($orderDetails["total_tax"])){ echo CURRENCY.$orderDetails["total_tax"];	} ?></td>
								</tr>
								<tr>
									<td class=" col-sm-7 text-left"><strong>Grand Total</strong></td>
									<td class=" col-sm-2 text-left"><?php if(isset($orderDetails["total_due_with_tax"])){ echo CURRENCY.$orderDetails["total_due_with_tax"];	} ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php if($showPurchaseBtnBool){	?>
				<form role="form" action="<?php echo $FormAction; ?>" method="post" name="form" id="frm_main_content">
	<input type="hidden" name="HashDigest" value="<?php echo $szHashDigest; ?>" />
	<input type="hidden" name="MerchantID" value="<?php echo $MerchantID; ?>" />
	<input type="hidden" name="Amount" value="<?php echo $szAmount; ?>" />
	<input type="hidden" name="CurrencyCode" value="<?php echo $szCurrencyCode; ?>" />
	<input type="hidden" name="EchoCardType" value="<?php echo $szEchoCardType; ?>" />
	<input type="hidden" name="OrderID" value="<?php echo $szOrderID; ?>" />
	<input type="hidden" name="TransactionType" value="<?php echo $szTransactionType; ?>" />
	<input type="hidden" name="TransactionDateTime" value="<?php echo $szTransactionDateTime; ?>" />
	<input type="hidden" name="CallbackURL" value="<?php echo $szCallbackURL; ?>" />
	<input type="hidden" name="OrderDescription" value="<?php echo $szOrderDescription; ?>" />
	<input type="hidden" name="CustomerName" value="<?php echo $szCustomerName; ?>" />
	<input type="hidden" name="Address1" value="<?php echo $szAddress1; ?>" />
	<input type="hidden" name="Address2" value="<?php echo $szAddress2; ?>" />
	<input type="hidden" name="Address3" value="<?php echo $szAddress3; ?>" />
	<input type="hidden" name="Address4" value="<?php echo $szAddress4; ?>" />
	<input type="hidden" name="City" value="<?php echo $szCity; ?>" />
	<input type="hidden" name="State" value="<?php echo $szState; ?>" />
	<input type="hidden" name="PostCode" value="<?php echo $szPostCode; ?>" />
	<input type="hidden" name="CountryCode" value="<?php echo $szCountryCode; ?>" />
	<input type="hidden" name="EmailAddress" value="<?php echo $szEmailAddress; ?>" />
	<input type="hidden" name="PhoneNumber" value="<?php echo $szPhoneNumber; ?>" />
	<input type="hidden" name="EmailAddressEditable" value="<?php echo $szEmailAddressEditable; ?>" />
	<input type="hidden" name="PhoneNumberEditable" value="<?php echo $szPhoneNumberEditable; ?>" />
	<input type="hidden" name="CV2Mandatory" value="<?php echo $szCV2Mandatory ?>" />
	<input type="hidden" name="Address1Mandatory" value="<?php echo $szAddress1Mandatory; ?>" />
	<input type="hidden" name="CityMandatory" value="<?php echo $szCityMandatory; ?>" />
	<input type="hidden" name="PostCodeMandatory" value="<?php echo $szPostCodeMandatory; ?>" />
	<input type="hidden" name="StateMandatory" value="<?php echo $szStateMandatory; ?>" />
	<input type="hidden" name="CountryMandatory" value="<?php echo $szCountryMandatory; ?>" />
	<input type="hidden" name="ResultDeliveryMethod" value="<?php echo $ResultDeliveryMethod; ?>" />
	<input type="hidden" name="ServerResultURL" value="<?php echo $szServerResultURL; ?>" />
	<input type="hidden" name="PaymentFormDisplaysResult" value="<?php echo $szPaymentFormDisplaysResult; ?>" />
	<input type="hidden" name="ServerResultURLCookieVariables" value="" />
	<input type="hidden" name="ServerResultURLFormVariables" value="" />
	<input type="hidden" name="ServerResultURLQueryStringVariables" value="" />
					<input name="submit" value="Purchase" class="btn btn-danger pull-right" type="submit" STYLE="margin-top:15px;">	
				</form>
				<?php } ?>
	 			<?php } else	{	?>
	 				<div class="alert alert-danger alert-dismissable">
  						<i class="glyphicon glyphicon-exclamation-sign"></i> No products for checkout, please first add some items in your cart!
					</div>
	 			<?php }	?>
			</div>
		</div>
	</div>
</section>
<?php 
require_once("include/top_footer.php");
require_once("include/footer.php");
?>
</body>
</html>
