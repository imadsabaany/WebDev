<?PHP
require_once('../includes/initialize.php');
if($session->is_logged_in())
{
    echo "<input id='sessionUsername' value=\"".$_SESSION['username']."\" type='text' style='position:absolute;visibility:hidden;'>";
	echo "<input id='sessionDisplayName' value=\"".$_SESSION['displayName']."\" type='text' style='position:absolute;visibility:hidden;'>";
} 
if(isset($_GET['query']))
{
    $query = test_input($_GET['query']); 
	echo "<input id='query' value=\"".$query."\" type='text' style='position:absolute;visibility:hidden;'>";
	$result=array_merge(Item::find_not_completed_by_name($query),Item::find_completed_by_name($query));
}
function drawTablesearch($result)
{
	echo "<table id=\"searchTable\" border='1'>
	<tr>
	<th>Seller Name</th>
	<th>Item Name</th>
	<th>description</th>
	<th>Current bid</th>
	<th>sell time</th>
	<th>Item page</th>
	</tr>";

	foreach ($result as $row) {
		$row->sellTime=subDate(date("Y-m-d H:i:s"),$row->sellTime);
		echo "<tr>";
		echo "<td>" . $row->owner . "</td>";
		echo "<td>" . $row->itemName . "</td>";
		echo "<td>" . $row->description . "</td>";
		if($row->sellTime===0) 
			echo "<td> Auction Completed</td>";
		else
			echo "<td>" . $row->currentBid ."$". "</td>";
		if($row->sellTime===0) 
		{
			if($row->currentBid==$row->openingPrice)
				$row->sellTime="Time out item not sold";
			else
				$row->sellTime="Sold for ". $row->maxBid."$";
		}
		echo "<td>" . $row->sellTime;
		echo "<td> <a href='itemPage.php?itemId=".$row->itemId."'>Go to item page</a> </td>";
		echo "</tr>";
	}
	echo "</table>";		 
	if(!$result || count($result) <= 0)
	{
		echo "<div id=\"noResults\">";
		echo "No search results";
		echo "</div>";
	}	
}

?>



<html>
	<head>
		<script src="js/search.js"></script>
		<title>
			Search
		</title>
		<link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="header">
		<h1><a href="homepage.php">eSaleZ</a></h1>
	</div>
	<div id="main">
		<?php if(isset($_GET['query'])){
			echo "<br><div>Search results for \"".$query."\" </div><br>";
		}
		else{
			echo "Error on page link";
		}
		?> 
		<div id="searchResults"> 
			<?php if(isset($_GET['query'])) drawTablesearch($result);?>
						<script>activeInterval();</script>
		</div>
	</div>
	<div id="footer">Copyright &#169; <?php echo date("Y",time()); ?> A&amp;I</div>
  </body>
</html>
