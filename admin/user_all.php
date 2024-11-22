<?php 
include_once("connection.php");
include_once("users.php");
if(isset($_POST['all']))
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
else if(isset($_POST['report']))
{
	echo $customer->displayReportAll($conn);
}

else{
		  echo $customer->displayCustomersAll($conn);
    
}