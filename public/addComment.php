<?php 

//this file will get ajax request and add comment to DB.
require_once('../includes/initialize.php');
function htmlElements($string)
{
	$count=0;
	$string = preg_replace('/&quot;[\s]*&gt;/', '">', $string,-1,$count);
	if($count>0){
		$string = preg_replace('/&lt;a [\s]*href[\s]*=[\s]*&quot;/i', '<a href="', $string);
	}
	$string = preg_replace('#&lt;[/]a[\s]*&gt;#i','</a>',$string);
	$string = preg_replace('/&lt;i[\s]*&gt;/i', '<i>', $string);
	$string = preg_replace('#&lt;[/]i[\s]*&gt;#i','</i>',$string);
	$string = preg_replace('/&lt;u[\s]*&gt;/i', '<u>', $string);
	$string = preg_replace('#&lt;[/]u[\s]*&gt;#i','</u>',$string);
	$string = preg_replace('/&lt;b[\s]*&gt;/i','<b>',$string);
	$string = preg_replace('#&lt;[/]b[\s]*&gt;#i','</b>',$string);
	return $string;
}
$flag=0;
$commentErr = $authorErr = $postedErr = $itemIdErr= "";
$comment = $author = $posted = $itemId = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if (empty($_POST["comment"]) || trim($_POST["comment"])=="" )
	{
		$commentErr = "Can't add empty comment.\n";
		$flag=1;
	}
	else
	{
		$comment = trim($_POST["comment"]);
		$comment=htmlspecialchars($comment);
		$comment=nl2br($comment);
		$comment=htmlElements($comment);
	}
	if (empty($_POST["posted"]))
	{
		$postedErr = "Error with post datetime.\n";
		$flag=1;
	}
	else
	{
		$posted = test_input($_POST["posted"]);
	}
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
	if(!$flag)
	{
		if(isset($_SESSION['username']))
		{
			$author=$_SESSION['username'];
			$myComment=new Comment;$myComment->update_values($itemId,date("Y-m-d H:i:s"),$author,$comment);
			if($myComment->create())
				echo "Comment added";
			else 
				echo "Error adding comment.";
			$itemId = $posted = $author = $comment= "";
		}
		else
		{
			echo "You must be logged in inorder to add new comment";
		}
	}
	else{
		echo ($commentErr . $authorErr . $postedErr . $itemIdErr) ; 
	}
}

?>