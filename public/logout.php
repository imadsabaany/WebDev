<?php
session_start();
if(isset($_SESSION['username']))
{
	$_SESSION['username']="";
	session_unset(); 
    // this would destroy the session variables 
	session_destroy();
}
if (isset($_COOKIE['user'])) {
	unset($_COOKIE['user']);
	setcookie('user', '', time() - 3600);
} 
?>