<?php
include_once("connection.php");
include_once("discount.php");
if(!isset($_POST['ok']))
{
	echo $discount->display_form($conn);
	
}
else
{
	
	echo $discount->addDiscount($conn);
	
}