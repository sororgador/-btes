<?php
include_once("connection.php");
include_once("class_event.php");
 if(!isset($_POST['event']))
 {
  echo $event->display($conn); 
 }
 else
 {
 echo $event->cancelEvent($conn);
 }
