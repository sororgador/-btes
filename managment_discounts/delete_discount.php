<?php
include_once("connection.php");
include_once("discount.php");
 if(!isset($_POST['search']))
 {
  echo $discount->displayformsearch($conn); 
 }
 else
 {
 echo $discount->deleteDiscount($conn);
 }