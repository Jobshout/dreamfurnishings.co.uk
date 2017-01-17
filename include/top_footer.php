<footer>
	<div class="container">
        <div class="row margn-btm15" >
            <div class="col-sm-3">
                 <h2>Quick Links</h2>
                <ul>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('about-us.html','content.htm?code=about-us');?>" title="Company Profile">Company Profile</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('sitemap.htm','sitemap.htm');?>" title="Sitemap">Sitemap</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('news.htm','news.htm');?>" title="News">News</a></li>
                 <li><a href="<?php echo gb_fn_linkCacheHandler('contact.htm','contact.htm');?>" title="Contact">Contact</a></li>
                 <?php if($termsPage= $mongoCRUDClass->db_findone("web_content", array("code" => "privacy-policy", "status" => "true"))){	?>
        			<li><a href="<?php echo gb_fn_linkCacheHandler('privacy-policy.html','content.htm?code=privacy-policy'); ?>" title="Privacy Policy">Privacy Policy</a><li>
        		<?php } ?>
                 </ul>
            </div>
            <?php $latestProductsList = $db->Products->find(array('publish_on_web' => true))->sort(array("sort_order" => -1,"modified_timestamp" => -1))->limit(5);
            if($latestProductsList->count()>0){	?>
            <div class="col-sm-3">
				<h2>Products</h2>
                	<ul>
                 	<?php foreach($latestProductsList as $prod){
                 		$link=$prod["uuid"];
                 		if(isset($prod['product_code']) && $prod['product_code']){
                 			$link="product-" . $prod["product_code"];
                 		}	?>
                     <li><a href="<?php echo gb_fn_linkCacheHandler($link.'.html','product.htm?code='.$link);?>" title="<?php echo $prod['ProductName']; ?>"><?php echo ucfirst($prod['ProductName']); ?></a></li>
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
            	© 2017 Dream Furnishings. All Rights Reserved.
                <?php } ?>
                
        		 <?php if($termsPage= $mongoCRUDClass->db_findone("web_content", array("code" => "terms-and-conditions", "status" => "true"))){	?>
        			<a href="<?php echo gb_fn_linkCacheHandler('terms-and-conditions.html','content.htm?code=terms-and-conditions'); ?>" title="Terms & Conditions" class="golden-link">Terms & Conditions</a>
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
                © 2017 Dream Furnishings. All Rights Reserved.
                <?php } ?>&nbsp;Site Managed by <a target="_blank" href="http://www.tenthmatrix.co.uk/" title="Tenthmatrix" class="powered-by-link"><img src="images/Powered-by-tenthmatrix.png" style="vertical-align:text-bottom;" alt="Tenthmatrix"></a> </div>
		</div>
	</div>
</footer>