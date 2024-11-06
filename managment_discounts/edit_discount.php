<?php
include_once("connection.php");
include_once("discount.php");
include_once("search_update.php");
 
 if(isset($_POST['ok']))
  {
	echo $discount->update($conn);
   
  }
  else if(isset($_POST['search']))
  {
   echo $discount->dataEdit($conn);
  }
  else{
	  displayformsearch1($conn);
  }
  ?>