<?php
require_once('../includes/initialize.php');
		$flag=0;
		$JSONresponse= array('bidAdded' => "", 'newCurrent' => "", 'newSellTime' => "");
		$bidErr = $itemIdErr ="";
		$bid =$itemId = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if (empty($_POST["bid"]))
			{
				$bidErr = "bid is required.\n";
				$flag=1;
			}
			else
			{
				$bid = test_input($_POST["bid"]);
				if (!preg_match('/^[0-9]+(\.)?[0-9]*\$$/',$bid))
				{
				$bidErr = "Only numbers followed by $ allowed.\n"; 
				$flag=1;
				}
				$bid=trim($bid, "$");
			}
		 
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
				if(isset($_SESSION['username']))
				{
					$bidder=$_SESSION['username'];
					$item=Item::find_by_id($itemId);
					if($item->currentBid >= $bid)
					{
						echo "Too low bid\n";
					}
					else{
						if($item->currentBid<$bid && $item->maxBid>=$bid)
						{
							$item->currentBid=$bid+0.01;
						}
						elseif($item->maxBid<$bid)
						{
							$item->currentBid=$item->maxBid+0.01;
							$item->maxBid=$bid;
							$item->winner=$bidder;
						}
						$item->update();
						$myBid=new Bid;$myBid->update_values($itemId,$bidder,$bid,date("Y-m-d H:i:s"));
						if($myBid->save())
						{
							$JSONresponse['bidAdded']="bid added";
							$JSONresponse['newCurrent']=$item->currentBid;
							echo json_encode($JSONresponse);
						}
						else
							echo "Error adding bid.";
						$itemId = $bidder = $bid= "";
					}
			}
			else{
				$JSONresponse['bidAdded']="You must be logged in inorder to bid";
				echo json_encode($JSONresponse);
			}
		}
		else{
			$JSONresponse['bidAdded']=$bidErr.$itemIdErr;
			echo json_encode($JSONresponse);
		}	
	}
?>


