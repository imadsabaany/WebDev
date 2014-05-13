<?php
require_once('../includes/initialize.php');
$jsonResp=array('total'=>"",'available'=>"");
$jsonResp['total']=count(Item::find_completed());
$jsonResp['available']=count(Item::find_not_completed());
echo json_encode($jsonResp);
?>