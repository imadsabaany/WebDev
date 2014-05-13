<?php
// If it's going to need the database, then it's 
// probably smart to require it before we start.
require_once('database.php');

class User {
	protected static $table_name="users";
	public $username;
	public $password;
	public $displayName;
	
	public	function update_values($username,$password,$displayName) {
		$this->username=$username;
		$this->password=$password;
		$this->displayName=$displayName;
	}
	
	
	
	public static function authenticate($username="", $password="") {
		
    	global $database;
    	$verify=false;
		$username =test_input($username);	 
    	$password =test_input($password);
    	$sql  = "SELECT * FROM users WHERE username=? LIMIT 1";
    	$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt,'s', $username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		if((!empty($row)) && (crypt($password,$row['password'])==$row['password']))
		{
			$verify=true;
		}
		return $verify ? self::instantiate($row) : false;
	}
	
	
	public static function find_all() {
		global $database;
		$result = $database->query("SELECT * FROM ".self::$table_name);
		return self::records_to_objects($result);
	}
  
	public static function find_by_username($id) {
		if($id == null)
		{
			return false;
		}
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE username=? LIMIT 1");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 's', $id);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		 if(!$result || $database->num_rows($result) <= 0){
			return false;
		} else {
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
	  return User::find_by_username($this->username) ? $this->update() : $this->create();
	}
	
	public function create(){
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		global $database;
		$sql = "INSERT INTO ".self::$table_name." (username, password, displayName) VALUES ( ?, ?,?)";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'sss', $this->username, password_hash($this->password), $this->displayName);
		mysqli_stmt_execute($stmt);
		if($stmt->affected_rows==1) {
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
		$sql = "UPDATE ".self::$table_name." SET username=?, password=?,displayName=? WHERE username=?";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'ssss', $this->username, $this->password, $this->displayName,$this->username);
		mysqli_stmt_execute($stmt);
		return ($stmt->affected_rows == 1) ? true : false;
	}
	public function delete(){
		global $database;
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
	  $sql = "DELETE FROM ".self::$table_name." WHERE username=? LIMIT 1  ";
		if(Comment::find_by_id($this->username)){
			$stmt = $database->prepared_stat_query($sql);
			mysqli_stmt_bind_param($stmt, 's', $this->username);
			mysqli_stmt_execute($stmt);
			return ($stmt->affected_rows == 1) ? true : false;
		}
	}
}

?>