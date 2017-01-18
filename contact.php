<?php 
require_once("include/config_inc.php");
require_once("include/main_header.php");

if(isset($_POST)){
	$code = isset($_POST['code']) ? $_POST['code'] : '';
	$uuidStr = isset($_POST['uuid']) ? $_POST['uuid'] : ''; 
	$availableOoptionsStr = isset($_POST['ava_options']) ? $_POST['ava_options'] : '';
	
	if(isset($code) && $code!=''){
		$dbProductData = $mongoCRUDClass->db_findone("Products", array("product_code" => $code), array("sku" => 1));
		$productSKUStr=$dbProductData['sku'];
	}elseif(isset($uuidStr) && $uuidStr!=''){
		$dbProductData = $mongoCRUDClass->db_findone("Products", array("uuid" => $uuidStr), array("sku" => 1));
		$productSKUStr=$dbProductData['sku'];
	}
}
?>
</head>
<body>
<?php require_once("include/header.php"); ?>	
  
<section style="position:relative; border-top:1px solid #D3D3D3;">
<?php $getGMapStr=get_token_value('contact-page-map');
if($getGMapStr!=""){	?>
	<div><?php echo $getGMapStr;?></div>
<?php }else{	?>
<div><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9925.053172848005!2d0.02288896930158619!3d51.5450708796415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a7bb3b9c104d%3A0x9197755b02923767!2s58+Green+St%2C+London+E7+8BZ!5e0!3m2!1sen!2suk!4v1455754813401" style="border:0" allowfullscreen="" width="100%" frameborder="0" height="300"></iframe> </div>
<?php	} ?>
	<div CLASS="container">
		<div CLASS="row content">
			<div CLASS="col-md-8">
				<h2 >Contact us</h2>
    			<div>
      				<p>We are here to answer any questions you may have about Dream Furnishings. Reach out to us and we'll respond as soon as we can.</p>
      				<p>Even if there is something you have always wanted and can't find it on  Dream Furnishings, let us know and we promise we'll do our best to find it for you and send you there.</p>
	  				If there is something 
    			</div>
				<form style="padding-top:10px;" id="contactform" name="contactform" method="post"> 
				 <?php if(isset($productSKUStr) && $productSKUStr!=""){
				 	echo '<div class="alert alert-info" role="alert">Please fill and submit the following form, we will contact you for further process!</div>';
				 }	 ?>
					<div CLASS="row displayMsg">
<div CLASS="col-md-6">
<div class="form-group"> <label for="c_name">Name</label> <span STYLE="color:red; font-size:17px;">*</span>
<input class="form-control" id="c_name" name="c_name" placeholder="" type="text"> </div>
</div>


<div CLASS="col-md-6">

 <div class="form-group"> <label for="c_email">Email</label> <span STYLE="color:red; font-size:17px;">*</span>
 <input class="form-control" id="c_email" name="c_email" placeholder="" type="email"> </div>
</div>
</div>


<div CLASS="row">
<div CLASS="col-md-12">
 <div class="form-group">
 <label for="c_message">Message:</label> <span STYLE="color:red; font-size:17px;">*</span>
 <TEXTAREA  class="form-control" STYLE="height:120px;" id="c_message" name="c_message">
 <?php if(isset($productSKUStr) && $productSKUStr!=""){	
 	echo "I would like to enquire about Product: ".$productSKUStr." (SKU)";
 }	?>
 </TEXTAREA>
</div>
 </div>
 </div>
  <?php if(isset($availableOoptionsStr) && $availableOoptionsStr!=""){	 ?>
 <div CLASS="row">
<div CLASS="col-md-12">
 <div class="form-group">
 <label for="c_options">Selected Product Options:</label>
 <TEXTAREA  class="form-control" readonly id="c_options" name="c_options">
 <?php if(isset($availableOoptionsStr) && $availableOoptionsStr!=""){	
 	echo $availableOoptionsStr;
 }	?>
 </TEXTAREA>
</div>
 </div>
 </div>
 <?php } ?>
 <div CLASS="row">
 <div CLASS="col-md-6">

 <div class="form-group"> <label for="e_val">
How much is <span class="rand1"></span>+<span class="rand2"></span>?</label> <span STYLE="color:red; font-size:17px;">*</span>
 <input class="form-control" id="e_val" placeholder="" name="e_val" type="text"> </div>

 </div>
 </div>
 <div CLASS="row pull-right" STYLE="margin-bottom:15px;">
 
 <div CLASS="col-md-2 text-right">
<button type="submit" class="btn submitbtn" id="submitBtn">Submit</button>
 </div>
 </div>
 
 
 </form>        

</div>
<div CLASS="col-md-4" >
<div STYLE="background:#f5f5f5; border:1px solid #ddd; margin-top:25px; padding:10px; border-radius:5px; clear:both;">
<h2 STYLE="margin-top:5px;">Address</h2>
	<?php $getMbStr=get_token_value('dreamfurnishing-mobile');
	if($getMbStr!=""){	?>
      <p><STRONG>Mobile :</STRONG></p>
      <p><a HREF="#"><?php echo $getMbStr;?></a></p>
	<?php } ?>
	<?php $getLandlineStr=get_token_value('dreamfurnishing-landline');
if($getLandlineStr!=""){	?>
      <p><STRONG> Landline :</STRONG></p>
      <p><a href="tel:<?php echo $getLandlineStr;?>" title="Click to call us"><?php echo $getLandlineStr;?></a></p>
	<?php } ?> 
	<?php $getSkypeStr=get_token_value('dreamfurnishing-skype');
	if($getSkypeStr!=""){	?>
      <p><STRONG>Skype</STRONG></p>
      <p><a href="skype:<?php echo $getSkypeStr;?>?call" title="Click to call us on Skype"><?php echo $getSkypeStr;?></a></p>
    <?php } ?>
    <?php if(defined("ADDRESS")){	?>
      <p><STRONG>Address</STRONG></p>
      <p>
        <address><?php echo ADDRESS;?></address>
      </p>
    <?php } ?>
        <div class=" social-blk-row" STYLE="margin-bottom:15px;">
			<div class="clearfix">
			<?php $tokensQry= $db->Tokens->find(array("code" => array('$in' => array('dreamfurnishing-twitter','dreamfurnishing-googleplus','dreamfurnishing-linkedin','dreamfurnishing-facebook'))));
				if($tokensQry->count()>0){
				foreach($tokensQry as $token){	
					if(isset($token["contentTxt"]) && $token["contentTxt"]!=""){
						$classStr=""; $iconClassStr="";
						if(isset($token["code"]) && $token["code"]=="dreamfurnishing-twitter"){
							$classStr="twitter";
							$iconClassStr="fa-twitter";
						}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-googleplus"){
							$classStr="google-plus";
							$iconClassStr="fa-google-plus";
						}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-linkedin"){
							$classStr="linkedin";
							$iconClassStr="fa-linkedin";
						}elseif(isset($token["code"]) && $token["code"]=="dreamfurnishing-facebook"){
							$classStr="facebook";
							$iconClassStr="fa-facebook";
						}
						?>
						<a href="<?php echo $token["contentTxt"];?>" target="_blank" class="icon-button <?php echo $classStr; ?>"><i class="fa <?php echo $iconClassStr; ?>"></i><span></span></a>
						<?php
					}
				}	?>
			<?php } ?>
			</div>
		</div>
</div>
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
$(document).ready(function(){
	randomnum();
		$( "#contactform" ).validate( {
        	errorElement: "em",
			rules: {
				c_name: "required",
				c_email : { required :true, email: true},
				c_message : "required",
				e_val : "required"
			},
			submitHandler: function(form) {
				$(".alert").remove();
				var total=parseInt($('.rand1').html())+parseInt($('.rand2').html());
				var total1=$('#e_val').val();
				if(total!=total1){
					$(".displayMsg").before('<div class="alert alert-danger" role="alert">Incorrect sum entered!</div>');
					randomnum();
					return false;
				}else{
					$("#submitBtn").attr("disabled",true);
					var avalStr="";
					<?php if(isset($availableOoptionsStr) && $availableOoptionsStr!=""){ ?>
						avalStr=$("#c_options").val();
					<?php } ?>
					$.ajax({
						url: 'submit-enquiry.htm',
						type: 'POST',
						data: {"name" : $("#c_name").val(), "email" : $("#c_email").val(), "message" : $("#c_message").val() , "options" : avalStr  },
						dataType: 'json',
						cache: false,
						success: function(response){
							$("#submitBtn").attr("disabled",false);
							if(response.success){
								randomnum();
								$("#contactform").trigger('reset');
								$("#c_message").val("");
								$("#c_options").val("");
								$("#c_options").hide();
								$(".displayMsg").before('<div class="alert alert-success" role="alert">'+response.success+'</div>');
							}else if(response.error){
								$(".displayMsg").before('<div class="alert alert-danger" role="alert">'+response.error+'</div>');
							}else if(response.required){
								$(".displayMsg").before('<div class="alert alert-info" role="alert">'+response.required+'</div>');
							}
						
						}
					});
				}
  			}
  		});
});
</script>
</body>
</html>
