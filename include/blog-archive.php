		<?php if($BlogResults = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)), 'code' => array('$ne' => ""), "type" => "blog"))->sort(array("posted_timestamp" => 1))){
      		$selectedYear='';
			if(isset($_GET['y']) && $_GET['y']!=''){
				$selectedYear= $_GET['y'];
			}
			if($BlogResults->count()>0){
		?>
		<div class="widget-blk">
      		<h2>Archives</h2>
        	<ul class="archive">
          		<?php	$data='';
					$i=0;
					$prev_year='';
					$prev_month='';
					$i=0;
					$data='';
					foreach($BlogResults as $BlogResult){
						$curr_year= date('Y',$BlogResult["posted_timestamp"]);
						if($prev_year <> $curr_year){
							if($i!=0){
  								$data.='('.$i.')</a>';
 							}
							if($data <> ''){
								$data.='</li></ul></li>';
							}
							$data.='<li><a href="javascript:void(0)" onClick=\'$("#year-'.$curr_year.'").slideToggle()\'>'.$curr_year.'</a>';
  							if($selectedYear==$curr_year){
  								$data.='<ul id="year-'.$curr_year.'">';
  							}else{
  								$data.='<ul id="year-'.$curr_year.'" style="display:none;">';
  							}
  							$prev_year= $curr_year;
  							$prev_month='';
  							$i=0;
 						}
 						$curr_month= date('m',$BlogResult["posted_timestamp"]);
 						if($prev_month <> $curr_month){
 							if($i!=0){
  								$data.='('.$i.')</a></li>';
 							}
 							$data.='<li><a href="blogs.php?y='.$curr_year.'&m='.$curr_month.'">'. date('M',$BlogResult["posted_timestamp"]);
 							$prev_month= $curr_month;
  							$i=1;
  						}else{
  							$i++;
 						}
					}
					$data.='('.$i.')</a></li></ul></li>';
					echo $data;
				?>
        	</ul>
      	</div>
      	<?php } 
      	}	?>