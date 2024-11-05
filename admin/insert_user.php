<?php
include_once("connection.php");
include_once("users.php");

if(!isset($_POST['insert']))
{
	 $admin->callvalidateFormInsert($conn);
  echo $admin->formInsert($conn);
 
}
else{
  echo $admin->insert($conn);
}