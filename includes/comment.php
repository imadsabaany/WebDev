<?php

require_once('database.php');
class Comment{
	protected static $table_name="comments";
	public $id;
	public $itemId;
	public $created;
	public $author;
	public $body;
	

	public	function update_values($itemId,$created,$author,$body) {
		$this->itemId=$itemId;
		$this->created=$created;
		$this->author=$author;
		$this->body=$body;
	}
	
	
	public static function print_all_comments($itemId){
	global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE itemId=? ORDER BY created DESC");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'i', $itemId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		while($row=mysqli_fetch_array($result)){
			echo "<tr>";
			echo "<td class=\"commentBodyTd\">";
			echo "<p class=\"commentBodyText\">".$row['body']."</p>";
			echo "</td>";
			echo "<tr>";
			echo "<td class=\"commentDetailsTd\">";
			echo "<strong class=\"commentDetailsText\">Posted by: <u>".$row['author']."</u> at ".$row['created']."</strong>";
			echo"</td>";
			echo "</tr>";
			echo "</tr>";
			
			
			/*
			echo "<br>";
			echo "<br>".$row['body']."";
			echo "<b><font  size=\"2\" color=\"#888888\"><br>".$row['author']."(".$row['created'].")"."</font></b><br>";
		*/}
		
	
	
	}
	
	public static function find_all() {
		global $database;
		$result = $database->query("SELECT * FROM ".self::$table_name);
		return self::records_to_objects($result);
	}
  
  
	public static function find_latest_for_item($date,$itemId) {
		if($itemId == null)
		{
			return false;
		}
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE created>? AND itemId=? ORDER BY created");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'si', $date,$itemId);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		 if(!$result || $database->num_rows($result) <= 0){
			return false;
		} else {
			//$row=$result->fetch_array();	
				return self::records_to_objects($result);
			//return self::instantiate($row);
	  }	
	}
  
  
  
  
	public static function find_by_id($id) {
		if($id == null)
		{
			return false;
		}
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE id=? LIMIT 1");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'i', $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		 if(!$result || $database->num_rows($result) <= 0){
			return false;
		} else{
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
	  return isset($this->id) ? $this->update() : $this->create();
	}
	
	public function create(){
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		global $database;
		$sql = "INSERT INTO ".self::$table_name." (itemId, created, author, body) VALUES (?, ?, ?,?)";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'isss', $this->itemId, $this->created, $this->author, $this->body);
		mysqli_stmt_execute($stmt);
		if($stmt->affected_rows==1) {
			$this->id = $database->insert_id();
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
		$sql = "UPDATE ".self::$table_name." SET itemId=?, created=?, author=?, body=? WHERE id=?";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'isssi', $this->itemId, $this->created, $this->author, $this->body,$this->id);
		mysqli_stmt_execute($stmt);
		echo $stmt->affected_rows;
		return ($stmt->affected_rows == 1) ? true : false;
	}
	public function delete(){
		global $database;
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		$sql = "DELETE FROM ".self::$table_name." WHERE id=? LIMIT 1  ";
		if(Comment::find_by_id($this->id)){
			$stmt = $database->prepared_stat_query($sql);
			mysqli_stmt_bind_param($stmt, 'i', $this->id);
			mysqli_stmt_execute($stmt);
			return ($stmt->affected_rows == 1) ? true : false;
		}
	}
	
}

?>