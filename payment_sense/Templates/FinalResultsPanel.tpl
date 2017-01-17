<?php
/*
 * Disclaimer: PaymentSense provides this code as an example of a working integration module.
 * Responsibility for the final implementation, functionality and testing of the module resides with the merchant/merchants website developer.
*/
	/*
	*session_start();
	*$EmailAddress = $_SESSION['email'];
	*$PhoneNumber = $_SESSION['phone_number'];
	*/
	$Amount = number_format($Amount/100, 2, '.', ',');
?>
<html>
	<head>
		<title>Transaction Result Page</title>
	</head>
	<body>
		<table>
			<tr>
				<td>
					<h2>Example Results Page</h2>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%;">
						<tr>
							<td style="text-align:center;">
								<?php
								if ($StatusCode == '0')
								{
									echo '<strong>Your Payment has been successful</strong><br/> 
											Please make a note of the authorisation number below 
											and feel free to contact us if you have any queries regarding your order.<br/>'.$Message.
											'<br/><h2><a href="payment.php">Process Another</a></h2>';
								}
								else if($StatusCode == '5')
								{
									echo '<strong>Your Payment has been Declined</strong><br/>
											Reason: '.$Message.'<br/>
											Please check your details and try again If the issue continues please contact your bank for more informtaion.<br/>
											<h2><a href="payment.php">Try Again</a></h2>';
								} 
								else if ($StatusCode == '20') 
									{
										echo 'A duplicate transaction means that a transaction with these details
												has already been processed by the payment provider. The details of
												the original transaction are given below:';
																								
										if ($PreviousStatusCode == '0')
										{		
											echo '<strong>Your transaction was successful</strong><br/>'
													.$PreviousTransactionMessage.
													'<br/><h2><a href="payment.php">Process Another</a></h2>';
										}
										else if ($PreviousStatusCode == '5')
										{		
											echo '<strong>Your transaction was declined</strong><br/>
													Reason: '.$PreviousTransactionMessage.'<br/>
													Please contact your Bank for more information<br/>
													<h2><a href="payment.php">Try Again</a></h2>';
										}
									}
								else
								{
									echo '<strong>An Unknown Error has Occurred</strong><br/>
											Please try again and contact us if the issue continues<br/>
													<h2><a href="payment.php">Try Again</a></h2>';
								}
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<hr/>
					<h3>All Details passed back to your site regarding the transaction</h3>
					<hr/>
				</td>
			</tr>
			<tr>
				<td>
					<table style="width:100%;" border="1">
						<tr><td style="width:20%;"><strong>Detail</strong></td><td style="width:20%;"><strong>Value</strong></td><td style="width:60%;"><strong>Comment</strong></td></tr>
						<tr><td>StatusCode:</td><td><?php echo $StatusCode;?></td><td>This is used to determin the status of the transaction so that you can display the relevent message to the customer</td></tr>
						<tr><td>Message:</td><td><?php echo $Message;?></td><td>This provides a message to accompany the StatusCode</td></tr>
						<tr><td>PreviousStatusCode:</td><td><?php echo $PreviousStatusCode;?></td><td>If the transaction is deemed a duplicate then the PreviousStatusCode will used to determin the transaction result.</td></tr>
						<tr><td>PreviousMessage:</td><td><?php echo $PreviousMessage;?></td><td>This is the Message that accompanies the PreviousStatusCode if the transaction is a duplicate. To provide further information.</td></tr>
						<tr><td>CrossReference / Transaction ID:</td><td><?php echo $CrossReference;?></td><td>This is used to act as a reference to the transaction within the Merchant Management system it can also be used for when using CrossReference Transactions</td></tr>
						<tr><td>Amount:</td><td><?php echo $Amount;?></td><td>The amount is passed back in pence and converted to pounds and pence.</td></tr>
						<tr><td>CurrencyCode:</td><td><?php echo $CurrencyCode;?></td><td>This is the CurrencyCode that the transaction was processed in. This is useful if you have a number of currencies on your site and need to be able to ensure that the transaction was processed in the correct currency that was previously passed.</td></tr>
						<tr><td>OrderID:</td><td><?php echo $OrderID;?></td><td>This can be used as a unique reference to the order that was passed to the gateway page and will allow the database to be updated</td></tr>
						<tr><td>TransactionType:</td><td><?php echo $TransactionType;?></td><td>This returns the TransactionType that was passed to the Payment Page. This can be set as SALE or PREAUTH.</td></tr>
						<tr><td>TransactionDateTime:</td><td><?php echo $TransactionDateTime;?></td><td>This is the date and Time that the Transaction was sent to our system.</td></tr>
						<tr><td>OrderDescription:</td><td><?php echo $OrderDescription;?></td><td>This is similar to the OrderID however and can hold further information about the order</td></tr>
						<tr><td>CustomerName:</td><td><?php echo $CustomerName;?></td><td>This is the name that was entered on the Payment Form page.</td></tr>
						<tr><td>Address1:</td><td><?php echo $Address1;?></td><td>This is the first line of the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>Address2:</td><td><?php echo $Address2;?></td><td>This is the second line of the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>Address3:</td><td><?php echo $Address3;?></td><td>This is the third line of the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>Address4:</td><td><?php echo $Address4;?></td><td>This is the fourth line of the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>City:</td><td><?php echo $City;?></td><td>This is the City that the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>State:</td><td><?php echo $State;?></td><td>This is the State that the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>PostCode:</td><td><?php echo $PostCode;?></td><td>This is the PostCode that the customers billing address that was entered on the Payment Form page.</td></tr>
						<tr><td>CountryCode:</td><td><?php echo $CountryCode;?></td><td>This is the CountryCode that the customers billing address that was entered on the Payment Form page.</td></tr>
						
						<tr><td colspan="3"><hr><h3>ResultDeliveryMethod Dependent Variables</h3><hr></td></tr>
						<?php
						If ($ResultDeliveryMethod == "POST")
						{?>
						<tr><td>CardType:</td><td><?php echo $CardType;?></td><td></td></tr>
						<tr><td>CardClass:</td><td><?php echo $CardClass;?></td><td></td></tr>
						<tr><td>CardIssuer:</td><td><?php echo $CardIssuer;?></td><td></td></tr>
						<tr><td>CardIssuerCountryCode:</td><td><?php echo $CardIssuerCountryCode;?></td><td></td></tr>
						<?php }
						else
						{ ?>
						<tr><td>CardType:</td><td>N/A</td><td>Only returned with POST ResultDelieveryMethod</td></tr>
						<tr><td>CardClass:</td><td>N/A</td><td>Only returned with POST ResultDelieveryMethod</td></tr>
						<tr><td>CardIssuer:</td><td>N/A</td><td>Only returned with POST ResultDelieveryMethod</td></tr>
						<tr><td>CardIssuer Country Code:</td><td>N/A</td><td>Only returned with POST ResultDelieveryMethod</td></tr>
						<?php } ?>
						<tr><td>EmailAddress</td><td><?php echo $EmailAddress;?></td><td>Only returned with POST ResultDelieveryMethod <br/>- Example uses SESSION cookies for the display of these when using SERVER or SERVER_PULL.</td></tr>
						<tr><td>PhoneNumber</td><td><?php echo $PhoneNumber;?></td><td>Only returned with POST ResultDelieveryMethod <br/>- Example uses SESSION cookies for the display of these when using SERVER or SERVER_PULL.</td></tr>
					</table>
				</td>
			</tr>
		</table>
		
					
<?php
			/*You can use the code below to send an email to the customer regarding the process of the transaction*/

			if ($StatusCode == "0") 
			{
				$subject = 'Payment Successfully Processed'; //Paymentsense Amendment
				$message = 'Thank you for your payment of £'. $Amount . ' for ' . $OrderDescription.'\r\n
							Your transaction has been successfully processed\r\n
							Please email (your@email.com) should you have any queries \r\n\r\n
							\r\nSIGNATURE\r\n(your name)\r\n(your position)\r\n(company name)';
			}
			else 
			{
				$subject = 'Payment Failed'; 
				$message = 'Your Order for '. $Amount .' has failed due to '. $Message.'\r\n
				Please contact (your contact details) for more details';
			}

			$to = $EmailAddress;

			$headers = 'From: your@email.com' . '\r\n' .
   	 					'Reply-To: your@email.com';

			mail ($to ,$subject , $message, $headers);
			session_destroy();

			/*
			*UPDATE Database order Table if applicable 
			*$host="";
			*$user="";
			*$password="";
			*$database_connection = mysql_connect($host,$user,$password);
			*
			*if (!$database_connection)
  			*{
  			*	die('Could not connect: ' . mysql_error());
  			*}
			*
			*if ($StatusCode == "0") 
			*{
			*	// Enter code to run UPDATE query on the database for successful transaction (status, stock etc)
			*}
			*else 
			*{
			*	// Enter code to run UPDATE query on the database for Failed transaction
			*}
			*/
?>




