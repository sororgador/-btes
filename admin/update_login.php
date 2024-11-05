<?php
include_once("connection.php");
include_once("users.php");
if(!isset($_POST['update1']))
{
  echo $admin->formUpdateLogin($conn);
}
else 
{
	$admin->validateForm($username, $password);
	$username=$_POST['name'];
	$password=$_POST['password'];
     echo $admin->updatelogin($conn);
}