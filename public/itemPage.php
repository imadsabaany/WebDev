<?php
//session_start();
require_once('../includes/initialize.php');
if(isset($_SESSION['username']))
{
    echo "<input id='sessionUsername' value=".$_SESSION['username']." type='text' style='position:absolute;visibility:hidden;'>";
} 
$itemId=0;
if(empty($_GET['itemId'])){
	echo "No Item ID was provided.";
	redirect_to('homepage.php');
}
$itemId=test_input($_GET['itemId']);
$item=Item::find_by_id($itemId);
if(!$item){
	echo "The item could not be located.";
	redirect_to('homepage.php');
}
?>


<html>
	<head>
		<title>
			Item page
		</title>
		<link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
		<script src="js/itemPageScripts.js"></script>
	</head>
	<body>
		<div id="header">
			<h1><a href="homepage.php">eSaleZ</a></h1>		
		</div>
		<div id="main">
		<?php if($itemId && $item){
			itemtable($item);
			bid_button($item);
			echo"<script> activeIntervals(); </script>";
		}?>
			<div   id="comments">
			<table id="commentsTable" border="0" >
				<col width="1000">			
				<tr>
					<td><textarea  placeholder="Your Comment" rows="4" cols="40" id="newCommentText"></textarea></td>	
				</tr>
				<tr>
					<td><button style="visibility:hidden" id="commentButton"  onclick="addCommentToDB()">Add comment</button></td>
				</tr>					
				<?php if($itemId)
					Comment::print_all_comments($itemId);
				?>					
				<div id="newCommentPlace">					
				</div>
			</table>
		<br>
		<?php
			add_comment_field();
		?>
			</div>
		</div>
	<div id="footer">Copyright &#169; <?php echo date("Y",time()); ?> A&amp;I</div>
  </body>
</html>


