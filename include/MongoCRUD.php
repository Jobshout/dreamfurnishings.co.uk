<?php
//event_type : 0 = create, 1= update, 2=delete
//sync_state, 0 means not synced and 1 means synced

class MongoCRUD	{
	var $maintainChangeLog=true;
	var $collectionsAllowedArr      = array();
	
	function db_maintain_change_log($values)
	{
		global $db;
		$collection = $db->db_event_log;
		$insertQuery= $collection->insert($values);
		return $insertQuery;
	}
	
	function db_insert($collection_name,$values)
	{
		global $db;
		$collection = $db->$collection_name;
		$insertQuery= $collection->insert($values);
		
		if($insertQuery && $this->maintainChangeLog){
			if (in_array($collection_name, $this->collectionsAllowedArr)){
				$this->db_maintain_change_log(array("created_timestamp" => time(), "modified_timestamp" => time(), "table_row_id" => $values['_id'], "table_name" => $collection_name, "event_type" => 0, "sync_state" => 0));
			}
		}
		return $insertQuery;
	}
	function db_delete($collection_name,$condition)
	{
		global $db;	
		$collection = $db->$collection_name;
		$table_id=$collection->findOne($condition);
		$removeQuery=$collection->remove($condition);
		if($removeQuery && $this->maintainChangeLog){
			if (in_array($collection_name, $this->collectionsAllowedArr)){
				$this->db_maintain_change_log(array("created_timestamp" => time(), "modified_timestamp" => time(), "table_row_id" => $table_id['_id'], "table_name" => $collection_name, "event_type" => 2, "sync_state" => 0));
			}
		}
	}
	
	function db_update($collection_name, $condition, $newdata, $action='$set')
	{
		global $db;
		$collection = $db->$collection_name;
		
		//$updateQuery=$collection->update($condition,$newdata);
		$updateQuery=$collection->update($condition, array($action => $newdata));
		if($updateQuery && $this->maintainChangeLog){
			if (in_array($collection_name, $this->collectionsAllowedArr)){
				$table_id=$collection->findOne($condition, array("_id"=>1));
				$this->db_maintain_change_log(array("created_timestamp" => time(), "modified_timestamp" => time(), "table_row_id" => $table_id['_id'], "table_name" => $collection_name, "event_type" => 1, "sync_state" => 0));
			}
		}
		return $updateQuery;
	}
	
	function db_findone($collection_name,$condition,$field_name=array())
	{
		global $db;
		$collection = $db->$collection_name;
		if(count($field_name)>0){
			$res = $collection->findOne($condition,$field_name);
		}else{
			$res = $collection->findOne($condition);
		}
		return $res;
	}
	function db_count($collection_name,$condition)
	{
		global $db;
		$collection = $db->$collection_name;
		$res = $collection->count($condition);
		return $res;
	}
	function db_getMax($collection_name,$field_name,$returnField)
	{
		global $db;
		$collection = $db->$collection_name;
		$res = $collection->find(array(),$field_name)->sort(array("_id"=>-1))->limit(1);
	
		foreach($res as $v)
		{
			return($v[$returnField]);
		}
	}
	function db_find($collection_name,$condition=array(),$field_name=array())
	{	
		global $db;
		$collection = $db->$collection_name;
			
		$output = "";
		
		if(count($field_name)>0){
			$res = $collection->find($condition,$field_name);
			$co=0;
			foreach($res as $v){
				foreach($field_name as $p=>$k){
					$output[$co][$p] = $v[$p];  
				}
				$co++;
			}
		}else{
			$res = $collection->find($condition);
			$output[]=$res;
		}
		return $output;
	}
}
?>