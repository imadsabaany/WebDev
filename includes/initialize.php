<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

date_default_timezone_set('Asia/Jerusalem');
require_once('config.php');
require_once('session.php');
require_once('comment.php');
require_once('bid.php');
require_once('database.php');
require_once('user.php');
require_once('functions.php');
require_once('item.php');

function test_input($data)
		{
			 $data = trim($data);
			 $data = stripslashes($data);
			 $data = htmlspecialchars($data);
			 return $data;
		}
		
function arithmeticDate($date,$number)
{//add/subtract number of minutes from date.and returns new date.
		$time = strtotime($date);
		$time = $time + ($number * 60);
		$result = date("Y-m-d H:i:s", $time);
		return $result;
}
function subDate($date1,$date2)
{//subtract date1 from date2.return number of minutes/seconds.
	
	$time1 = strtotime($date1);
	//echo $date."<br>".$row->sellTime;
	$time2=strtotime($date2);
	$time = $time2 - $time1;
	if($time<60 && $time>0){
	$result="0m".$time."s left";
	}
	elseif($time<=0){
	$result=0;
	}
	else{
		$result =intval($time/60)."m left";
	}
	//echo "<br>".$result;
	return $result;
}

?>