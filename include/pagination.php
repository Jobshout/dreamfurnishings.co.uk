<?php
	// How many adjacent pages should be shown on each side?

	$adjacents = 3;
	
	/* Setup vars for query. */
	if(empty($limit)==true) $limit = 10;									//how many items to show per page

	if(isset($_REQUEST['page']))
		$page = $_REQUEST['page'];
	else
		$page = '';
	
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0

	$startLim = $start;
	$endLim = $limit;
	//Logic ends here


	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	
	$lpm1 = $lastpage - 1;						//last page minus 1
	$showing_upto= $limit+$startLim;
	if($showing_upto>$total_pages){ $showing_upto = $total_pages; }
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	
	if($lastpage > 1)
	{	
		$pagination .= "<nav><ul class='pagination'>";
		//previous button
		if ($page > 1) 
			$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$prev')\">&laquo;</a></li>";
		else
			$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" style='color:#23527c; background-color:#eee;'>&laquo;</a></li>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" style='color:#23527c; background-color:#eee;'>$counter</a></li>&nbsp;";		
				else
					$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$counter')\">$counter</a></li>&nbsp;";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" style='color:#23527c; background-color:#eee;'>$counter</a></li>";		
					else
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$counter')\">$counter</a></li>";					
				}
				//$pagination.= "...";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$lpm1')\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$lastpage')\">$lastpage</a></li>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage(1)\">1</a></li>";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage(2)\">2</a></li>";
				//$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\"  style='color:#23527c; background-color:#eee;'>$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$counter')\">$counter</a></li>";					
				}
				//$pagination.= "...";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$lpm1')\">$lpm1</a></li>";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$lastpage')\">$lastpage</a></li>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage(1)\">1</a></li>";
				$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage(2)\">2</a></li>";
				//$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\"  style='color:#23527c; background-color:#eee;'>$counter</a></li>";
					else
						$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$counter')\">$counter</a></li>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" onclick=\"movePage('$next')\">»</a></li>";
		else
			$pagination.= "<li class='page-item'><a href=\"javascript:void(0);\" style='color:#23527c; background-color:#eee;'>»</a></li>";
		$pagination.= "</ul></nav>";		
	}
?>