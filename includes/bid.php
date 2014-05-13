<?php

require_once('database.php');
class Bid{
	protected static $table_name="bids";
	public $bidItem;
	public $bidder;
	public $bidValue;
	public $auctionDate;
	

	public	function update_values($bidItem,$bidder,$bidValue,$auctionDate) {
		$this->bidItem=$bidItem;
		$this->bidder=$bidder;
		$this->bidValue=$bidValue;
		$this->auctionDate=$auctionDate;
	}
	
	public static function find_all() {
		global $database;
		$result = $database->query("SELECT * FROM ".self::$table_name);
		return self::records_to_objects($result);
	}
  
public static function print_all_userbids($username){
	global $database;
	//$item;$timeLeft;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE bidder=?");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 's', $username);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		while($row=mysqli_fetch_array($result)){
		$item=Item::find_by_id($row['bidItem']);
			echo "<tr bgcolor=\"#CCCCCC\">";
			echo "<td><a href=\"itemPage.php?itemId=".$row['bidItem']."\">".$item->itemName."</a></td>";
			echo "<td>".$row['bidValue']."$</td>";
			$timeLeft=subDate(date("Y-m-d H:i:s"),$item->sellTime);
			if($timeLeft===0)
			{
				if($item->winner==$username)
					echo "<td>Auction completed You won.</td>";
				else
					echo "<td>Auction completed You lost.</td>";
			}			
			else 
				echo "<td>".$timeLeft." left</td>";
			echo "</tr>";
		}
	
	
	
	}
  
	public static function find_by_id($bidItem,$bidder) {
		if($bidItem == null or $bidder == null)
		{
			return false;
		}
		global $database;
		$sql = ("SELECT * FROM ".self::$table_name." WHERE bidItem=? and bidder=? LIMIT 1");
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'is', $bidItem, $bidder);
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
	  return (Bid::find_by_id($this->bidItem,$this->bidder)) ? $this->update() : $this->create();
	}
	
	public function create(){
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		global $database;
		$sql = "INSERT INTO ".self::$table_name." (bidItem, bidder, bidValue,auctionDate) VALUES (?, ?, ?, ?)";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'isds', $this->bidItem, $this->bidder, $this->bidValue,$this->auctionDate);
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
		$sql = "UPDATE ".self::$table_name." SET bidValue=?, auctionDate=? WHERE bidItem=? and bidder=?";
		$stmt = $database->prepared_stat_query($sql);
		mysqli_stmt_bind_param($stmt, 'dsis', $this->bidValue,$this->auctionDate,$this->bidItem,$this->bidder);
		mysqli_stmt_execute($stmt);
		return ($stmt->affected_rows == 1) ? true : false;
	}
	public function delete(){
		global $database;
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		$sql = "DELETE FROM ".self::$table_name." WHERE bidItem=? and bidder=? LIMIT 1  ";
		if(Comment::find_by_id($this->bidItem,$this->bidder)){
			$stmt = $database->prepared_stat_query($sql);
			mysqli_stmt_bind_param($stmt, 'is', $this->bidItem, $this->bidder);
			mysqli_stmt_execute($stmt);
			return ($stmt->affected_rows == 1) ? true : false;
		}
	}
	
}

?>