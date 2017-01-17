<!-- 
 Disclaimer: PaymentSense provides this code as an example of a working integration module.
 Responsibility for the final implementation, functionality and testing of the module resides with the merchant/merchants website developer.
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Hosted - Example Checkout Page</title>
	</head>
	<body> 
		<form action="Process.php" method="post" name="ExampleForm">
			<table>
				<tr>
					<td colspan="2">
						<h1>Hosted - Example Checkout Page</h1>
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h2>Order Details</h2>
					</td>
				</tr>
				<tr>
					<td>
						Amount:
					</td>
					<td>
						<select name="amount" id="amount">
							<option value="0.01"> 0.01 </option>
							<option value="0.02"> 0.02 </option>
							<option value="0.05"> 0.05 </option>
							<option value="0.10"> 0.10 </option>
							<option value="0.20"> 0.20 </option>
							<option value="0.50"> 0.50 </option>
							<option value="1.00"> 1.00 </option>
							<option value="2.00"> 2.00 </option>
							<option value="5.00"> 5.00 </option>
							<option value="10.00"> 10.00 </option>
							<option value="20.00"> 20.00</option>
							<option value="50.00"> 50.00 </option>
						</select>
					</td>
				<tr>
					<td>
						Currency Code:
					</td>
					<td>
						<select name="currency_code" id="currency_code">
							<option value="826"> GBP &pound; </option>
							<option value="840"> USD &#36;</option>
							<option value="978"> EUR &euro; </option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Order ID:
					</td>
					<td>
						<input type="text" name="order_id" id="order_id" value="100001"/>
					</td>
				</tr>
				<tr>
					<td>
      					Order Description:
      				</td>
					<td>
      					<input type="text" name="order_description" id="order_description" value="test order"/>
      				</td>
      			</tr>
      			<tr>
      				<td colspan="2">
      					<h2>Delivery Details</h2>
      				</td>
      			</tr>
      			<tr>
      				<td>
						Customer Name:
					</td>
					<td>
						<input type="text" name="customer_name" id="customer_name" value="John Watson"/>
					</td>
				<tr>
					<td>
						Address Line 1:
					</td>
					<td>
						<input type="text" name="address_line_1" id="address_line_1" value="32 Edward Street"/>
					</td>
				</tr>
				<tr>
					<td>
						Address Line 2:
					</td>
					<td>
						<input type="text" name="address_line_2" id="address_line_2" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						Address Line 3:
					</td>
					<td>
						<input type="text" name="address_line_3" id="address_line_3" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						Address Line 4:
					</td>
					<td>
						<input type="text" name="address_line_4" id="address_line_4" value=""/>
					</td>
				</tr>
				<tr>
					<td>
						City:
					</td>
					<td>
						<input type="text" name="city" id="city" value="Cambourne"/>
					</td>
				</tr>
				<tr>
					<td>
						State/County:
					</td>
					<td>
						<input type="text" name="state" id="state" value="Cornwall"/>
					</td>
				</tr>
				<tr>
					<td>
						Post Code:
					</td>
					<td>
						<input type="text" name="post_code" id="post_code" value="TR14 8PA"/>
					</td>
				</tr>
				<tr>
					<td>
						Country Code:
					</td>
					<td>
						<select name="country_code" id="country_code">
							<option value="826"> UK </option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h2>Contact Details</h2>
					</td>
				</tr>
				<tr>
					<td>
						Email Address:
					</td>
					<td>
						<input type="text" name="email_address" id="email_address" value="test@test.com"/>
					</td>
				</tr>
				<tr>
					<td>
						Phone Number:
					</td>
					<td>
						<input type="text" name="phone_number" id="phone_number" value="02080000001"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;">
						<!-- Post transaction values to the Process.php page -->
						<br/>
						<input type="submit" value="Confirm and Pay"/>
					</td>
				</tr>
			</table>
		</form>
	</body>
</html>