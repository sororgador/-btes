<?php
include_once("connection.php");
include_once("class_seats.php");
if(!isset($_POST['book']))
{
 	 echo $seat->fillIn($conn);
}
else
{
   header('LOCATION:  /get/admin/register_customer.php');
}
?>
 