<?php

require_once('../includes/initialize.php');

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

function output_message($message="") {
  if (!empty($message)) { 
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

function drawtable($item){
	if($item){
		$row=$item;
		$row->sellTime=subDate(date("Y-m-d H:i:s"),$row->sellTime);
		echo "<table border='1'>
		<tr id='detailsRow'>
		<th>Item Name</th>
		<th>Owner</th>
		<th>Description</th>
		<th>Opening price</th>";
				if($row->sellTime===0)
					echo "<th>Winning price</th>";
				else
					echo"<th>Current bid</th>";
		if(isset($_SESSION['username'])&&$_SESSION['username']==$item->owner && $row->sellTime===0)
		{
			echo 	"<th>Winner</th>";
		}
		echo "<th>Sell time</th>
		
		</tr>";
		

	  {
		echo "<tr id='detailsRow'>";
		
		echo "<td hidden=\"true\"><div id=\"ITEMID\">" . $row->itemId . "</div></td>";
		echo "<td>" . $row->itemName. "</td>";
		echo "<td> <div id=\"owner\">" . $row->owner . "</div></td>";
		echo "<td>" . $row->description . "</td>";
		echo "<td>" . $row->openingPrice ."$". "</td>";
		if($row->sellTime===0)
			$row->currentBid=$row->maxBid;
		echo "<td id=\"CurrentPrice\">" . $row->currentBid ."$". "</td>";
		if(isset($_SESSION['username'])&&$_SESSION['username']==$item->owner)
		{
		if($row->sellTime===0 && $_SESSION['username']==$item->owner)
			echo "<td>" . $row->winner . "</td>";
		}
		if($row->sellTime===0){
			$row->sellTime="Auction completed";
			if(isset ($_SESSION['username']) && Bid::find_by_id($row->itemId,$_SESSION['username']))
			{
				if($row->winner==$_SESSION['username'])
					$row->sellTime="Auction completed<br>You won";
				else
					$row->sellTime="Auction completed<br>You lost";
			}
		}
		echo "<td id=\"sellTime\">" . $row->sellTime."</td>";
		echo "</tr>";
	  
		if($row->imageURL){
			echo "<tr id='imgRow'>";
			echo "<td> <img width='300px' src='" .$row->imageURL ."'>"."</td>";
			echo "</tr>";
		}
	  }
	}
	echo "</table>";
}

function itemtable($item){
	if($item){
		$row=$item;
		$row->sellTime=subDate(date("Y-m-d H:i:s"),$row->sellTime);
		echo "<div hidden=\"true\" id=\"ITEMID\">" . $row->itemId . "</div>";
		
		if($row->imageURL!="")
			echo "<img width='200px' width='200px' src='" .$row->imageURL ."'>";
		else
			echo "<img width='200px' width='200px' src='http://www.mnit.ac.in/new/PortalProfile/images/faculty/noimage.jpg'>";
		echo "<table style=\"position:absolute;top:125px;left:250px;\">";
		echo "<td>";
			echo "<tr id='detailsRow'>";
				echo "<th>Item Name: </th>";
				echo "<td>" . $row->itemName. "</td>";
			echo "</tr>";
			echo "<tr id='detailsRow'>";
				echo "<th>Owner: </th>";
				echo "<td> <div id=\"owner\">" . $row->owner . "</div></td>";
			echo "</tr>";
			echo "<tr id='detailsRow'>";
				echo "<th>Description: </th>";
				echo "<td>" . $row->description . "</td>";
			echo "</tr>";
			echo "<tr id='detailsRow'>";
				echo "<th>Opening price: </th>";
				echo "<td>" . $row->openingPrice ."$". "</td>";
			echo "</tr>";
			echo "<tr id='detailsRow'>";
				if($row->sellTime===0)
					echo "<th>Winning price: </th>";
				else
					echo"<th>Current bid: </th>";
				if($row->sellTime===0)
				{
					if($row->currentBid==$row->openingPrice)
						$row->currentBid="Item not sold";
					else
						$row->currentBid=$row->maxBid."$";
				}
				else
					$row->currentBid=$row->currentBid."$";
				echo "<td id=\"CurrentPrice\">" . $row->currentBid. "</td>";
			echo "</tr>";
		
		if(isset($_SESSION['username'])&&$_SESSION['username']==$item->owner && $row->sellTime===0)
		{
			echo "<tr id='detailsRow'>";
				echo "<th>Winner: </th>";
				echo "<td>" . $row->winner . "</td>";
			echo "</tr>";
		}
			echo "<tr id='detailsRow'>";
				echo "<th>Sell time: </th>";
				if($row->sellTime===0){
				$row->sellTime="Auction completed";
				if(isset ($_SESSION['username']) && Bid::find_by_id($row->itemId,$_SESSION['username']))
				{
					if($row->winner==$_SESSION['username'])
						$row->sellTime="Auction completed<br>You won";
					else
						$row->sellTime="Auction completed<br>You lost";
					}
				}
				echo "<td id=\"sellTime\">" . $row->sellTime."</td>";
			echo "</tr>";
		echo "</td>";
		echo "</tr>";
	  }
	  echo "</table>";
}
	

function showUserOptions($item){
if(isset($_SESSION['username']))
	{//show add comment button and add new bid...
		echo "<div  name=\"postItem\">
				<button  id=\"addComment\" onclick=\"addCommentToDB()\">Add comment</button>
			</div>";
		if((!(strpos($item->sellTime,'Auction completed') !== false)) && $_SESSION['username']!=$item->owner)
		{
			echo "<div  name=\"newBid\" style=\"position:absolute;left:30px;top:250px;\">
				<button  id=\"bidButton\" onclick=\"bidForThisItem()\">Bid item</button>
				<input id=\"bidText\" type=\"text\" >
			</div>";
		}


	}
}
function add_comment_field(){

if(isset($_SESSION['username']))
	{//show add comment button and add new bid...		
			echo "<script>showCommentText();</script>";
	}
}
function bid_button($item){
if(isset($_SESSION['username'])){
	if((!(strpos($item->sellTime,'Auction completed') !== false)) && $_SESSION['username']!=$item->owner)
		{
			echo "<div  name=\"newBid\" >
				<button  id=\"bidButton\" onclick=\"bidForThisItem()\">Bid item</button>
				<input id=\"bidText\" type=\"text\" >
			</div>";
		}
	}
}





?>