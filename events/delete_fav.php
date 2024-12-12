<?php
session_start();
include_once("connection.php");
include_once("class_fav.php");
 if(isset($_POST['delete']))
 {
	  $fav-> deleteFavorites($conn);
	  }
 else{
 $fav->displayFavoritesDelete($conn);
 }
?>
