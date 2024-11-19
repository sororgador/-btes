<?php 
session_start();
include_once("connection.php");
include_once("users.php");
if(isset($_POST['student']))
{
	 echo $customer->dataEditcustomers($conn);
 	
}
else if(isset($_POST['submit']))
{   
        echo $customer->updatecustomers($conn);
		 
}
else if(isset($_POST['delete']))
{
	echo $customer->deleteCustomers($conn);
}
else{
		  echo $customer->displayCustomers($conn);
    
}