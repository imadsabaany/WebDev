<?php
require_once("config.php");

class MySQLDatabase {
	
	private $connection;

	
	function __construct() {
		$this->open_connection();
	}
	//open connection and get our database
	public function open_connection() {
		$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS,DB_NAME);
		if (!$this->connection) {
			die("Database connection failed: " .mysqli_connect_error());
		} else {
			$db_select = mysqli_select_db($this->connection, DB_NAME);
			if (!$db_select) {
				die("Database selection failed: " .mysqli_connect_error());
			}
		}

	}
	//close connection
	public function close_connection() {
		if(isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
	}
	
	public function query($sql) {
		$result = mysqli_query($this->connection, $this->escape_value($sql));
		$this->confirm_query($result);
		return $result;
	}
	private function confirm_query($result) {
		if (!$result) {
			$output = "Database query failed: " .mysqli_error($this->connection). "<br /><br />";
			die( $output );
		}
	}
	public function fetch_array($result_set){
		return $result_set->fetch_assoc();
	}
	
	public function affected_rows() {
		return mysqli_affected_rows($this->connection);
	}
	
	public function insert_id() {
    // get the last id inserted over the current db connection
		return mysqli_insert_id ($this->connection);
  	}
	public function num_rows($result) {
    // get the last id inserted over the current db connection
		return mysqli_num_rows ($result);
  	}
	
	//prepers values for submission to sql mysql_prep
	public function escape_value($value) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string" );
		if($new_enough_php){
			if($magic_quotes_active){$value = stripslashes($value);}
				$value = mysqli_real_escape_string($this->connection,$value );
		}else{
			if(!$magic_quotes_active ){ $value = addslashes($value);}
		}
		return $value;
	}

	
	public function prepared_stat_query($sql){
		$result = mysqli_prepare($this->connection,$sql);
		if (!$result) {
			$output = "Database query failed: " .mysqli_error($this->connection). "<br /><br />";
			die( $output );
		} 
		else
			return $result;
	}
	
}

$database = new MySQLDatabase();



?>