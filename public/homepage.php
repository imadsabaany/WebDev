<?php
require_once("../includes/initialize.php");
echo ("<div style=\"position:absolute;top:130px;left:20px\">Statistics:<br><br>Total items for sell: <div id=\"available\">".count(Item::find_not_completed())."</div><br><br> Total sold items: <div id=\"total\">".count(Item::find_completed())."</div></div>");
if($session->is_logged_in())
{
    echo "<input id='sessionUsername' value=".$_SESSION['username']." type='text' style='position:absolute;visibility:hidden;'>";
	echo "<input id='sessionDisplayName' value=".$_SESSION['displayName']." type='text' style='position:absolute;visibility:hidden;'>";
} 
function userIslogged(){
	echo "<script>onloadlogin();</script>";
	echo "<div id=\"userBids\">
			<table border=\"0\" id=\"bidstable\" style=\"position:absolute;top:430px;left:340px\">
				<tr bgcolor=\"#888888\">
					<th width=\"200px\">Item name</th>
					<th width=\"200px\">Your bid</th>
					<th width=\"220px\">Auction time</th>
				</tr>";
			Bid::print_all_userbids($_SESSION['username']);
}
?>
<html>
	<head>
	
	<script src="js/userLog.js"></script>
	<script src="js/homePageAutoRefresh.js"></script>
		<title>
		eSaleZ
		</title>
		<link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="header">
		<h1>eSaleZ</h1>
	
		<div  name="postItem">
			<button  id="postItem" style="visibility:hidden; position:absolute;right:10px;top:120px" width="30px"; onclick="location.href = 'postItem.php';">Post Item</button>
		</div>
	
		<div id="searchHeader">
			<form id="search" method="get" action="Search.php">
		        <input id="searchText"  type="text"  name="query" size="21" maxlength="120"></h2>   
				<input id="searchButton"  type="submit" value="search" >
			</form>
		</div>
		
		<form id="loginForm"  method="post">
		<table  align="right" cellspacing="0">
			<tr>
				<td>
					<label style="color:white" for="username">Username</label>
				</td>
				<td>
					<label style="color:white" for="pass">Password</label>
				</td>
			</tr>
			<tr>
				<td>
					<input type="text" class="inputtext" name="username" id="username" value="" tabindex="1" />
				</td>
				<td>
					<input type="password" class="inputtext" name="password" id="password" tabindex="2" />
				</td>
				<td>
					<input type="button" onclick="loginn()" id="login" value="Login">
				</td>
			</tr>
			<tr>
				<td>
					<a style="color:white" rel="nofollow" href="register.php">Register</a>
				</td>
			</tr>
			</table>
		</form>
	</div>
	<div id="main">
		<h3>Recent Items</h3>
	</div>
	<!------------------------------------------------------------------------------>
	<div id="recent4items">
		<table border="5px" id="recentItems" align="center" style="position:absolute;top:170px;left:20%">
			<col width="180">
			<col width="180">
			<col width="180">
			<col width="180">
		<?php
		$recent=Item::find_most_recent();
		$tempRecent=array('0'=>new Item(),'1'=>new Item(),'2'=>new Item(),'3'=>new Item());
		if(isset($recent[0]))
			$tempRecent[0]=$recent[0];
		if(isset($recent[1]))
			$tempRecent[1]=$recent[1];
		if(isset($recent[2]))
			$tempRecent[2]=$recent[2];
		if(isset($recent[3]))
			$tempRecent[3]=$recent[3];
		echo"<tr>";
		echo"
			<td id=\"recentIm1cell\">
				<div id=\"recentIm1\">
						<a id=\"im1link\" href=\"itemPage.php?itemId=".$tempRecent[0]->itemId."\">
							<img id=\"im1\" width=\"180px\" height=\"200\" src=".$tempRecent[0]->imageURL.">
						</a>
				</div>
			</td>
			<td id=\"recentIm2cell\">
				<div id=\"recentIm2\">
						<a id=\"im2link\" href=\"itemPage.php?itemId=".$tempRecent[1]->itemId."\">
							<img id=\"im2\" width=\"180px\" height=\"200\" src=".$tempRecent[1]->imageURL.">
						</a>
				</div>
			</td>
			<td id=\"recentIm3cell\">
				<div id=\"recentIm3\">
						<a id=\"im3link\" href=\"itemPage.php?itemId=".$tempRecent[2]->itemId."\">
							<img id=\"im3\" width=\"180px\" height=\"200\" src=".$tempRecent[2]->imageURL.">
						</a>
				</div>
			</td>
			<td id=\"recentIm4cell\">
				<div id=\"recentIm4\">
						<a id=\"im4link\" href=\"itemPage.php?itemId=".$tempRecent[3]->itemId."\">
							<img id=\"im4\" width=\"180px\" height=\"200\" src=".$tempRecent[3]->imageURL.">
						</a>
				</div>
			</td>
		</tr>";?>
		
		</table>
	</div>

	<!----------------------------------------------------------------------->
	
	<div id="footer">Copyright &#169; <?php echo date("Y",time()); ?> A&amp;I</div>
	<script>activeIntervals();</script>
  </body>
  <?php
	if($session->is_logged_in())
	{
		userIslogged();
	} ?>
</html>

	
		
