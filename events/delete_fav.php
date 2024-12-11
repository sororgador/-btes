<?php
session_start();
include_once("connection.php");
include_once("class_fav.php");
 if(isset($_POST['delete']))
 {
	  $fav-> delete_favorites($conn);
	  }
 else{
 $fav->display_favorites_delete($conn);
 }
?>