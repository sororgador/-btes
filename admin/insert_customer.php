<?php
include_once("connection.php");
include_once("users.php");
$customer->clallvlidatinsert($conn);
if(!isset($_POST['insert']))
{
  echo $customer->formInsertCustomer($conn);
}
else{
  echo $customer->insert($conn);
  $_SESSION['qualification']=$_POST['qualification'];
}