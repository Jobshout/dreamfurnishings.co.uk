<?php                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
require_once("include/config_inc.php");
require_once("include/main_header.php");

if(isset($isUserSignedInBool) && $isUserSignedInBool==true){
	if(!isset($userLoggedIn)){
		header("Location: logout.htm");
		exit;
	}
}else{
	header("Location: login.htm?redirect=myaccount");
	exit;
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
             				<li><a href="<?php echo gb_fn_linkCacheHandler('index.htm','index.htm');?>" title="Home">Home</a></li>
							<li class="active">My account</li>
            			</ol>
					</div>
          		</div>
        	</div>
		</div>
	</div>
	<div class="container">
		<div class="row content">
			<div class="col-md-12  ">
				<div class="well">
        			<div class="row">
             			<div class="col-sm-12">
							<div class="my-account-pg-hding"><span class="glyphicon glyphicon-info-sign"></span> My Info</div>
							<div style="padding:0 15px;">
								<div class="row">
                   					<div class="col-xs-3 col-sm-2"><p><strong>Name:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"><p><?php if(isset($userLoggedIn) && isset($userLoggedIn['First name'])){ echo $userLoggedIn['First name']; } ?>&nbsp;<?php if(isset($userLoggedIn) && isset($userLoggedIn['Surname'])){ echo $userLoggedIn['Surname']; } ?></p></div>
                				</div>
                 				<div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>E-mail:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"> <p><?php if(isset($userLoggedIn) && isset($userLoggedIn['Email'])){ echo $userLoggedIn['Email']; } ?></p></div>
                				</div>
				                <div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>Telephone:</strong></p> </div>
                    				<div class="col-xs-9 col-sm-10"><p><?php if(isset($userLoggedIn) && isset($userLoggedIn['Mobile'])){ echo $userLoggedIn['Mobile']; } ?></p></div>
                				</div>
				                <div class="row">
                    				<div class="col-xs-3 col-sm-2"><p><strong>Address:</strong> </p></div>
                    				<div class="col-xs-9 col-sm-10">
										<p><?php if(isset($userLoggedIn["address_line_1"]) && $userLoggedIn["address_line_1"]!=""){ echo $userLoggedIn["address_line_1"];	} ?>
										<?php if(isset($userLoggedIn["address_line_2"]) && $userLoggedIn["address_line_2"]!=""){ echo ", ".$userLoggedIn["address_line_2"];	} ?>
										<?php if(isset($userLoggedIn["address_line_3"]) && $userLoggedIn["address_line_3"]!=""){ echo ", ".$userLoggedIn["address_line_3"];	} ?>
										<?php if(isset($userLoggedIn["county_or_state"]) && $userLoggedIn["county_or_state"]!=""){ echo ", ".$userLoggedIn["county_or_state"];	} ?>
										<?php if(isset($userLoggedIn["country"]) && $userLoggedIn["country"]!=""){ echo ", ".$userLoggedIn["country"];	} ?>
										<?php if(isset($userLoggedIn["post_zip_code"]) && $userLoggedIn["post_zip_code"]!=""){ echo ", ".$userLoggedIn["post_zip_code"];	} ?></p>
									</div>
                				</div>
			    				<div class="row">
                    				<div class="col-xs-3 col-sm-2"></div>
                    					<div class="col-xs-9 col-sm-10"><p>[ <a href="account.htm">Edit</a> ]</p>
                    			</div>
                			</div>
						</div>
                	</div>
                </div>
				<!--My activity-->
				<?php 	$transactions= $db->orders->find(array('uuid_client' => $session_values['user_uuid']))->sort(array('order_date' => -1));
						$total_trans=$transactions->count();
						if($total_trans>0) {
				?>
				<div class="row" id="transaction_activity">
					<div class="col-sm-12 ">
                  		<div class="my-account-pg-hding"><span class="glyphicon glyphicon-list-alt"></span> My Activity</div>   
                 		<div style="padding:0 15px;">
                 			<?php	 $i=0;
							foreach($transactions as $trans) {
								$i++;
								switch ($trans['status']) {
    								case 2:
        								$statusStr='<span class="alert-success">Completed</span>';
        								break;
    								case 2:
        								$statusStr='<span class="status-code2">Checkout form submitted, viewing confirmation page</span>';
        								break;
    								default:
        								$statusStr='<span class="status-code2">Added items in cart</span>';
								}
							?>
				 			<div class="row <?php if($i>15){ echo 'showhidekeys'; } ?>">
								<div class="col-sm-12"><strong><?php echo $i;?>.</strong> <span style="font-style:italic;"><span style="font-weight:600; margin-left:5px;">Order ID:</span> <a href="activity.htm?uuid=<?php echo $trans['uuid'];?>"><?php echo $trans['full_order_number']; ?></a>, <span style="font-weight:600;margin-left:5px;">Dated: </span><?php if(isset($trans["order_date"]) && $trans["order_date"]!=""){ echo date("d M Y", strtotime($trans["order_date"]));	} ?>, <span style="font-weight:600;margin-left:5px;">Current Status:</span> <?php echo $statusStr; ?> </span></div>
                			</div>
							
                			<?php } ?>
                			<?php if($total_trans>15){ ?>
								<br/>
								<a href="javascript:void(0)" onClick="moreOrders(); return false;" id="show_more">+ additional orders</a>
							<?php } ?>
						</div>
					</div>
 				</div>
 				<?php } ?>
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
var showBool=false;
function moreOrders(){
	if(showBool){
		showBool=false;
		$("#show_more").html("+ additional orders ");
	}else{
		showBool=true;
		$("#show_more").html("- additional orders ");
	}
	$('.showhidekeys').toggle();
}

$(document).ready(function(){
	$('.showhidekeys').hide();
});
</script>
</body>
</html>
