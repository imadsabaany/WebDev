<?php
require_once('../includes/initialize.php');
// define variables and set to empty values
$flag=0;
$welcomeMsg="";
$usernameErr = $passwordErr = $displayNameErr = "";
$username = $password = $displayName = "";

if(isset($database))
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (empty($_POST["username"]) || trim($_POST["username"])=="")
		{
			$usernameErr = "User name is required";
			$flag=1;
		}
		else
		{
			$username = test_input($_POST["username"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z0-9 ]*$/",$username))
			{
				$usernameErr = "Only letters,numbers and white space allowed"; 
				$flag=1;
			}
			else{
				//check user name at database
				$stmt = $database->prepared_stat_query("SELECT * from users where username=? ");
				mysqli_stmt_bind_param($stmt, "s", $username);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if(mysqli_num_rows($result) > 0)
				{
					$usernameErr = "User name already in use."; 
					$flag=1;
				}
			}
		}
		if (empty($_POST["password"]))
		{
			$passwordErr = "Password is required";
			$flag=1;
		}
		else
		{
			$password = test_input($_POST["password"]);
			// check if URL address syntax is valid (this regular expression also allows dashes in the URL)
			if((!preg_match_all('/[0-9]/', $password)>=1) || (!preg_match_all('/[A-Za-z]/', $password)>=1) || (!preg_match("/^[A-Za-z0-9]{5,}$/",$password)))
			{
				$passwordErr = "Weak password! Your password must include at least 5 charcters,one letter and one digit."; 
				$flag=1;
			}
		}	 
		if (empty($_POST["displayName"]) || trim($_POST["displayName"])=="")
		{
			$displayName = "";
		}
		else
		{
			$displayName = test_input($_POST["displayName"]);
		}
		if(!$flag)
		{
			if($displayName=="")
				$displayName = $username;
			$pw=crypt($password);
			$stmt = $database->prepared_stat_query("INSERT INTO users VALUES (?, ?, ?)");
			mysqli_stmt_bind_param($stmt, 'sss', $username, $pw , $displayName);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			$welcomeMsg="Welcome to eSaleZ ".$username." <br><br>Redirecting in 3 Seconds
			<meta http-equiv=\"refresh\" content=\"3;url=homepage.php\" />";
			$usernameErr = $passwordErr = $displayNameErr = "";
			$username = $password = $displayName = "";
		}
	}
} 
else 
{
echo "Unable to connect to database";
}

?>


<html>
	<head>
		<title>
		Registration
		</title>
		<link href="css/main.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
	<div id="header">
		<h1><a href="homepage.php">eSaleZ</a></h1>
		
	</div>
	<div id="main">
		<div>Registration</div><br>
			<form method="POST"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		  <table>
			<tr>
				<td>				
				<div>* required fields</div><br>
				</td>
			</tr>
		    <tr>
		      <td>Username*:</td>
		      <td>
		        <input type="text" name="username" maxlength="30" value="<?php echo htmlentities($username); ?>" />
		      </td>
				<td><span class="error"> <?php echo $usernameErr;?></span>
		      </td>
		    </tr>
		    <tr>
		      <td>Password*:</td>
		      <td>
		        <input type="password" name="password" maxlength="30" value="<?php echo htmlentities($password); ?>" />
				</td>
				<td><span class="error"> <?php echo $passwordErr;?></span>
		      </td>
		    </tr>
			<tr>
		      <td>DisplayName:</td>
		      <td>
		        <input type="text" name="displayName" maxlength="30" value="<?php echo htmlentities($displayName); ?>" />
		      </td>
		    </tr>
		    <tr>
		      <td colspan="2">
		        <input type="submit" name="submit" value="Register" />
		      </td>
		    </tr>
		  </table>
		</form>
		<?php echo $welcomeMsg;?>
	</div>
	<div id="footer">Copyright &#169; <?php echo date("Y",time()); ?> A&amp;I</div>
  </body>
</html>
