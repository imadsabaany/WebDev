<?php
require_once('../includes/initialize.php');
		$JSONresponse= array('item0' => "", 'item1' => "", 'item2' => "", 'item3' => "");
		$result=Item::find_most_recent();
		for($i=0;$i<4;$i++){
			$JSONresponse['item'.$i]['itemId']= $result[$i]->itemId;
			$JSONresponse['item'.$i]['imageURL']= $result[$i]->imageURL;
		}
		echo json_encode($JSONresponse);

?>