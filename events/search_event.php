<?php
include_once("connection.php");
include_once("class_event.php");
 if(!isset($_POST['search']))
 {
	 $event->displayformsearch($conn);
 }


 else 
 {  
	  
	 if(!isset($_POST['booking']))
    {
		 $event->search($conn);
		 
    }
	else 
	{   
  		header('LOCATION:  booking.php');
	}
 }