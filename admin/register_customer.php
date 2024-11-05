<?php
include_once("connection.php");
include_once("users.php");
$customer->callvalidateregister($conn);
if(!isset($_POST['submit']))
{
   echo $customer->register_form();

}
else
{
   echo $customer->register($conn);
?> <h2><a href="insert_customer.php"> الرجاء تعبئة النموذج</a> </h2><?php
}