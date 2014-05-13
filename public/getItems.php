<?php
require_once('../includes/initialize.php');

	$result="false";
	if(isset($_POST['query'])){
		$query = test_input($_POST['query']);
		$notCompleted=Item::find_not_completed_by_name($query);
		$completed=Item::find_completed_by_name($query);
		if($completed && $notCompleted)
			$result=array_merge($notCompleted,$completed);
		elseif($completed)
			$result=$completed;
		elseif($notCompleted)
			$result=$notCompleted;
	}
	if($result!="false"){
		foreach($result as $value)
			$value->sellTime=subDate(date("Y-m-d H:i:s"),$value->sellTime);
	}
	echo json_encode($result);
?>