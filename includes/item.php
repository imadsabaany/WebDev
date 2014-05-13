<?php

require_once('database.php');
class Item{
	protected static $table_name="items";
	public $itemId;
	public $owner;
	public $itemName;
	public $description;
	public $sellTime;
	public $openingPrice;
	public $currentBid;
	public $maxBid;
	public $winner;
	public $imageURL;
	
	public	function update_values($owner,$itemName,$description,$sellTime,$openingPrice,$currentBid,$maxBid,$winner,$imageURL) {
			$this->owner=$owner;
			$this->itemName=$itemName;
			$this->description=$description;
			$this->sellTime=$sellTime;
			$this->openingPrice=$openingPrice;
			$this->currentBid=$currentBid;
			$this->maxBid=$maxBid;
			$this->winner=$winner;
			$this->imageURL=$imageURL;
	}
	public static function find_all() {
		global $database;
		$result = $database->query("SELECT * FROM ".self::$table_name);
		return self::records_to_objects($result);
	}
	
		public static function find_completed() {
		global $database;
		//$result = $database->query("SELECT * FROM ".self::$table_name." WHERE sellTime<?");
		$now = date("Y-m-d H:i:s");
		$sql = ("SELECT * FROM ".self::$table_name." WHERE sellTime<=?");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 's', $now);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		return self::records_to_objects($result);
	}
	
	public static function find_not_completed() {
		global $database;
		//$result = $database->query("SELECT * FROM ".self::$table_name." WHERE sellTime<?");
		$now = date("Y-m-d H:i:s");
		$sql = ("SELECT * FROM ".self::$table_name." WHERE sellTime>?");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 's', $now);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		return self::records_to_objects($result);
	}
	
	public static function find_most_recent(){
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." ORDER BY itemId DESC LIMIT 0,4");
		$result = $database->query($sql);
		return self::records_to_objects($result);
	}
	
	public static function find_not_completed_by_name($query) {
		global $database;
		$fixedQ='%'.$query.'%';
		$now = date("Y-m-d H:i:s");
		$sql = ("SELECT * FROM ".self::$table_name." WHERE sellTime>? AND (itemName LIKE ? OR description LIKE ?) ORDER BY currentBid ASC ");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'sss', $now,$fixedQ,$fixedQ);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		return self::records_to_objects($result);
	}
	
		public static function find_completed_by_name($query) {
		global $database;
		$fixedQ='%'.$query.'%';
		$now = date("Y-m-d H:i:s");
		$sql = ("SELECT * FROM ".self::$table_name." WHERE sellTime<=? AND (itemName LIKE ? OR description LIKE ?) ORDER BY maxBid DESC ");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'sss', $now,$fixedQ,$fixedQ);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		return self::records_to_objects($result);
	}
	
	public static function find_by_id($id) {
		if($id == null)
		{
			return false;
		}
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE itemId=? LIMIT 1");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		if(!$result || $database->num_rows($result) <= 0){
			return false;
		}else{
			$row=$result->fetch_array();	
			return self::instantiate($row);
		}	
	}
	
  	public static function records_to_objects($result_set) {
		global $database;
		$object_array = array();
		while ($row = $database->fetch_array($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	
	private static function instantiate($record){
		if($record === null){
			return false;
		}
		$object = new self;
		foreach($record as $attribute=>$value){
			$object->$attribute = $value;
		}
		return $object;
	}
	
	public function save() {
	  return isset($this->itemId) ? $this->update() : $this->create();
	}

	public function create(){
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		global $database;
		if($this->imageURL=="")
			$this->imageURL="http://www.mnit.ac.in/new/PortalProfile/images/faculty/noimage.jpg";
		$sql = "INSERT INTO ".self::$table_name." (owner,itemName,description,sellTime,openingPrice,currentBid,maxBid,winner,imageURL) VALUES (?,?,?,?,?, ?,?, ?,?)";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'ssssdddss', $this->owner,$this->itemName,$this->description,$this->sellTime,$this->openingPrice,$this->currentBid,$this->maxBid,$this->winner,$this->imageURL);
		mysqli_stmt_execute($stmt);
		if($stmt->affected_rows==1) {
			$this->itemId = $database->insert_id();
			return true;
		} else {
			return false;
	  }
	}
	public function update(){
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection

		global $database;
		$sql = "UPDATE ".self::$table_name." SET owner=?, itemName=?, description=?, sellTime=?, openingPrice=?,currentBid=?,maxBid=?,winner=?, imageURL=? WHERE itemId=?";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'ssssdddssi', $this->owner, $this->itemName, $this->description, $this->sellTime, $this->openingPrice,$this->currentBid,$this->maxBid,$this->winner, $this->imageURL,$this->itemId);
		mysqli_stmt_execute($stmt);
		return ($stmt->affected_rows == 1) ? true : false;
	}
	public function delete(){
		global $database;
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		$sql = "DELETE FROM ".self::$table_name." WHERE itemId=? LIMIT 1  ";
		if(Comment::find_by_id($this->itemId)){
			$stmt = $database->prepared_stat_query($sql);
			mysqli_stmt_bind_param($stmt, 'i', $this->itemId);
			mysqli_stmt_execute($stmt);
			return ($stmt->affected_rows == 1) ? true : false;
		}
	}
	
}

?>