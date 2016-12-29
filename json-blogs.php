<?php
require_once("include/config_inc.php");
$startLim= isset($_GET['start']) ? $_GET['start'] : 0;
$limit= isset($_GET['end']) ? $_GET['end'] : 0;

if(isset($_GET['mon']) && isset($_GET['year']) && $_GET['mon']!='' && $_GET['year']!=''){
	$month = $_GET['mon'];
	$year = $_GET['year'];
	$StartDate = mktime(0,0,0,$month,1,$year);
    $EndDate = mktime(0,0,0,$month,31,$year);
    $dbResultsData = $db->web_content->find(array("posted_timestamp" => array('$gte' => $StartDate, '$lte' => $EndDate), '$or' => array(array("status" => "true"), array("status" => true)), "type" => "blog",'code' => array('$ne' => "")))->sort(array("sort_order" => 1))->limit($limit)->skip($startLim);
}else{
	$dbResultsData = $db->web_content->find(array('$or' => array(array("status" => "true"), array("status" => true)), "type" => "blog",'code' => array('$ne' => "")))->sort(array("sort_order" => 1))->limit($limit)->skip($startLim);
}
$total_records=$dbResultsData->count();

$output = array( 
	"iTotalRecords" => isset($total_records) ? $total_records : 0 
);

if($dbResultsData->count()>0){
	$i=0;
	foreach($dbResultsData as $document){
		$row = array();
       	$Published_timestamp=$document["posted_timestamp"];
       	$row['month']= date('M',$Published_timestamp);
		$row['date'] = date('d',$Published_timestamp);
		$bodyStr= $document["body"];
		$bodyStr=getBriefText($bodyStr,115);

		$row['body']=$bodyStr;
		$row['title']=$document["title"];
		$row['code']=$document["code"];
		$countCommentsNum=0;
		if(isset($document['objects']) && count($document['objects'])>0){
			foreach($document['objects'] as $blog_comment){
				if(($blog_comment["status"]=="true" || $blog_comment["status"]==true) && isset($blog_comment["object_content"]) && $blog_comment["object_content"]!=""){
					$countCommentsNum++;
				}
			}
		}		
		$row['image']="images/blog-default-image.jpg";
		$row['comments']=  $countCommentsNum;
		$i++;			
		$output['aaData'][] = $row;
	}
	$output['iTotalReturnedRecords']=$i;
}

if(count(isset($output))>0){
	echo json_encode($output);
}else{
	$output['error']="No more record found.";
	echo json_encode($output);
}

?>