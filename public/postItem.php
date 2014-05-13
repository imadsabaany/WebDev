<?php //session_start();
require_once('../includes/initialize.php');
if($session->is_logged_in())
{
    echo "<input id='sessionUsername' value=".$_SESSION['username']." type='text' style='position:absolute;visibility:hidden;'>";
	echo "<input id='sessionDisplayName' value=".$_SESSION['displayName']." type='text' style='position:absolute;visibility:hidden;'>";
	} 
?>
<html>
	<head>
		<title>
			Item Post
		</title>
				<link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
		<script src="js/postItemScripts.js"></script>
		
		
	</head>
	<body>
		<div id="header">
			<h1><a href="homepage.php">eSaleZ</a></h1>
		</div>
	
		<div id="loginForm">

		</div>
	<?php

		// define variables and set to empty values
		$flag=0;
		$welcomeMsg="";
		$itemNameErr = $descriptionErr = $sellTimeErr = $openingPriceErr= "";
		$itemName = $description = $sellTime = $openingPrice = $imageURL= "";
	
		if ($_SERVER["REQUEST_METHOD"] == "POST")
		{
		   if (empty($_POST["itemName"]) || trim($_POST["itemName"])=="")
			 {
			 $itemNameErr = "Item name is required";
			 $flag=1;
			 }
		   else{
			 $itemName = test_input($_POST["itemName"]);
			 // check if name only contains letters and whitespace
			 if (!preg_match("/^[a-zA-Z0-9 ]*$/",$itemName))
			   {
			   $itemNameErr = "Only letters,numbers and white space allowed"; 
				$flag=1;
				}
			}

	   if (empty($_POST["description"]) || trim($_POST["description"])=="")
		{
		 $descriptionErr = "Description is required";
		 $flag=1;
		}
	   else
		{
			$description = test_input($_POST["description"]);
		 // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
		}	 
		 if (empty($_POST["imageURL"]) || trim($_POST["imageURL"])=="")
		{
		 $imageURL="";
		}
	   else
		{
			$imageURL = test_input($_POST["imageURL"]);
		 // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
		}
	   if (empty($_POST["sellTime"]) || trim($_POST["sellTime"])=="")
		 {
		 $sellTimeErr = "Sell time is required";
		 $flag=1;
		 }
	   else
		 {
		 $sellTime = test_input($_POST["sellTime"]);
		 if (!preg_match("/^[1-9][0-9]*$/",$sellTime))
			{
			   $sellTimeErr = "Only numbers allowed"; 
				$flag=1;
			}
		 }
		if (empty($_POST["openingPrice"]))
		 {
		 $openingPriceErr = "Opening price is required";
		 $flag=1;
		 }
		else
		 {
		 $openingPrice = test_input($_POST["openingPrice"]);
		 if (!preg_match('/^[0-9]+(\.)?[0-9]*\$$/',$openingPrice))
			{
			   $openingPriceErr = "Only numbers followed by $ allowed"; 
				$flag=1;
			}
			$openingPrice=trim($openingPrice, "$");
		 }
		 if(!$flag)
		 {
			if(isset($_SESSION['username']))
			{
			$owner=$_SESSION['username'];
			$sellTime = arithmeticDate(date("Y-m-d H:i:s"),$sellTime);
			$myitem=new Item;$myitem->update_values($owner,$itemName,$description,$sellTime,$openingPrice,$openingPrice,$openingPrice,"No Winner",$imageURL);
			if($myitem->create())
				echo '<script>alert("Item has been added.\n Your bid end at  '.$sellTime.'");</script>';
			else echo '<script>alert("Error adding item.");</script>';
				$itemName = $description = $sellTime = $openingPrice = $imageURL= "";
			}
			else{
				echo '<script>alert("You must be logged in to be able to post new item.Please login.");</script>';
		 
			}
		 }
	}


		
?>
		<div id="main">
		<form id="postItemForm" onsubmit="return validForm();" name="postItemForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">		
			<table>
			<tr>
				<td>				
					<div>* required fields</div><br>
				</td>
			</tr>
		    <tr>
		      <td>Item name:</td>
		      <td><input type="text" name="itemName" maxlength="50" value="<?php echo $itemName;?>" id="itemName" /></td>
			  <td><span class="error">* <?php echo $itemNameErr;?></span></td>
		    </tr>		
			<tr>
		      <td>Description:</td>
		      <td><input type="text" name="description" maxlength="500" value="<?php echo $description;?>" id="description" /></td>
			  <td><span class="error">* <?php echo $descriptionErr;?></span></td>
		    </tr>		
			<tr>
		      <td>Sell time(in minutes):</td>
		      <td><input type="text" name="sellTime" maxlength="50" value="<?php if($sellTime) echo $sellTime; else echo '10'?>" id="sellTime" /></td>
			  <td><span class="error">* <?php echo $sellTimeErr;?></span></td>
		    </tr>		
			<tr>
		      <td>Opening price:</td>
		      <td><input type="text" name="openingPrice" maxlength="50" value="<?php if($openingPrice) echo $openingPrice."$"; else echo '0.01$'?>" id="openingPrice" /></td>
			  <td><span class="error">* <?php echo $openingPriceErr;?></span>
		      </td>
		    </tr>				
			<tr>
		      <td>Image URL:</td>
		      <td><input type="text" name="imageURL" maxlength="2083" value="<?php echo $imageURL;?>" id="imageURL" /></td> 
		    </tr>			
			<tr>
		      <td colspan="2"><input type='submit' name='Submit' value='Submit' /></td>
		    </tr>
			</table>
			<?php
			if(!isset($_SESSION['username'])){
				echo " <span class='error'>You must be logged in to be able to post new item.Please login.</span>";
			}?>			
		</form>		
		</div>
		<div id="footer">Copyright <?php echo date("Y",time()); ?>, A&amp;I</div>
	</body>
</html>