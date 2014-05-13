<?PHP
require_once('../includes/user.php');

function CheckLoginInDB($username,$password)
{
	$result = User::authenticate($username,$password);
    if(!$result)
    {
		echo "Wrong user Name or Password";
        return false;
    }
	echo "Welcome";
    return $result;
}


function Login()
{
     if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$username = test_input($_POST['username']);
		$password = test_input($_POST['password']);
		$result=CheckLoginInDB($username,$password);
		if(!$result)
		{
			return false;
		}
    session_start();
    $_SESSION['username']=$username;
	$_SESSION['displayName']=$result->displayName;
	setcookie("user", $username, time()+3600);
	setcookie("session", $_SESSION['username'], time()+3600);
    return true;
	}
}

function test_input($data)
{
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
Login();
?>