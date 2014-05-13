<?php
require_once('../includes/initialize.php');
$flag=0;
$JSONresponse= array('accepted'=>"",'currentPrice' => "", 'sellTime' => "");
$itemIdErr ="";
$itemId = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (empty($_POST["itemId"]))
		{
			$itemIdErr = "Item id is required.\n";
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
		if(!$flag)
		{
			$item=Item::find_by_id($itemId);
			$JSONresponse['accepted']="true";
			$JSONresponse['currentPrice']=$item->currentBid;
			$JSONresponse['sellTime']=subDate(date("Y-m-d H:i:s"),$item->sellTime);
		}
		else
		{
			$JSONresponse['accepted']=$itemIdErr;	
		}
	echo json_encode($JSONresponse);
}


?>