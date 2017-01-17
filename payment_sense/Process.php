<?php
/*
 * Disclaimer: PaymentSense provides this code as an example of a working integration module.
 * Responsibility for the final implementation, functionality and testing of the module resides with the merchant/merchants website developer.
*/
    session_start();//Paymentsense Amendment
	require_once ('PaymentFormHelper.php');
	include ('Config.php');

	$Width = 800;
	$FormAction = 'https://mms.'.$PaymentProcessorDomain.'/Pages/PublicPages/PaymentForm.aspx';
	include ('Templates/ProcessFormHeader.tpl');

	// the amount in *minor* currency (i.e. £10.00 passed as "1000")
	$szAmount = $_POST['amount'] * 100;
	// the currency	- ISO 4217 3-digit numeric (e.g. GBP = 826)
	$szCurrencyCode = $_POST['currency_code'];
	// echo card type
	$szEchoCardType = $EchoCardType;
	// order ID
	$szOrderID = $_POST['order_id'];
	// the transaction type - can be SALE or PREAUTH
	$szTransactionType = "SALE";
	// the GMT/UTC relative date/time for the transaction (MUST either be in GMT/UTC 
	// or MUST include the correct timezone offset)
	$szTransactionDateTime = date('Y-m-d H:i:s P');
	// order description
	$szOrderDescription = $_POST['order_description'];
	// these variables allow the payment form to be "seeded" with initial values
	$szCustomerName = $_POST['customer_name'];
	$szAddress1 = $_POST['address_line_1']; // Important for AVS Check 
	$szAddress2 = $_POST['address_line_2'];
	$szAddress3 = $_POST['address_line_3'];
	$szAddress4 = $_POST['address_line_4'];
	$szCity = $_POST['city'];
	$szState = $_POST['state'];
	$szPostCode = $_POST['post_code']; // Important for AVS Check
	// the country code - ISO 3166-1  3-digit numeric (e.g. UK = 826)
	$szCountryCode = $_POST['country_code'];
	//Email Address
    $_SESSION['email'] = $_POST['email_address'];//Paymentsense Amendment
	$szEmailAddress = $_POST['email_address'];
	//Phone Number
	$_SESSION['phone_number'] = $_POST['phone_number'];//Paymentsense Amendment
	$szPhoneNumber = $_POST['phone_number'];
	// use these to control which fields on the hosted payment form are
	//editable
	$szEmailAddressEditable = $EmailAddressEditable;
	$szPhoneNumberEditable = $PhoneNumberEditable;
	// mandatory
	$szCV2Mandatory = $CV2Mandatory;
	$szAddress1Mandatory = $Address1Mandatory;
	$szCityMandatory = $CityMandatory;
	$szPostCodeMandatory = $PostCodeMandatory;
	$szStateMandatory = $StateMandatory;
	$szCountryMandatory = $CountryMandatory;
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
		$szCallbackURL = PaymentFormHelper::getSiteSecureBaseURL().'callback.php';
	}
	else
	{
		$szCallbackURL = PaymentFormHelper::getSiteSecureBaseURL().'callback.php'; 
	}

	// get the string to be hashed
	$szStringToHash = PaymentFormHelper::generateStringToHash($MerchantID,
			        										  $Password,
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
	$szHashDigest = PaymentFormHelper::calculateHashDigest($szStringToHash,
                        								   $PreSharedKey, 
                        								   $HashMethod);

	include ('Templates/ProcessForm.tpl');
	include ('Templates/FormFooter.tpl');
?>