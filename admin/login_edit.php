<?php
include_once("connection.php");
@session_start();
include_once("users.php");

if(!isset($_POST['login']))
{
	 
    $admin->form1(); 
   
 
}
else{
  $_SESSION['username']=$_POST['name'];
  $_SESSION['password']=$_POST['password'];
  $admin->checkToUpdate($conn);  
 //$admin->callvalidateform($name,$password); 
}