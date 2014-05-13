<?php
require_once('../includes/initialize.php');
$itemIdErr=$itemId="";
$flag=0;



if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (empty($_POST["itemId"]))
		{
			$itemIdErr = "Item id is not available.\n";
			$flag=1;
		}
		else
		{
			$itemId = test_input($_POST["itemId"]);
			if (!(Item::find_by_id($itemId)))
			{
				$itemIdErr = "Item id".$itemId." not found at the database.\n"; 
				$flag=1;
			}
		}
	}
		if(!$flag)
		{
		$date=date("Y-m-d H:i:s");
		$time = strtotime($date);
		$time = $time - (5);
		$date = date("Y-m-d H:i:s", $time);
		$jsonResp=Comment::find_latest_for_item($date,$itemId);
		}
		else  $jsonResp=$itemIdErr;
		echo json_encode($jsonResp);
?>