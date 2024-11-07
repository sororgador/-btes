<?php
 
include_once("connection.php");
include_once("class_event.php");
include_once("search_update.php");
 
 if(isset($_POST['update1']))
  {
     echo $event->callvalidationformupdate($conn,@$title,@$date,@$time,@$location,@$numOfSeats,@$description);
  echo $event->update($conn);
  
   
 }
 else if(isset($_POST['search'])){
  
   echo $event->dataEdit($conn);
}
 else{
   
    displayformsearch1($conn);
  }
  ?>