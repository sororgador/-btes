<?php
session_start();
include_once("connection.php");  
include_once("class_fav.php");

// استدعاء الدالة لعرض المفضلات
$fav->displayFavorites($conn);
?>
