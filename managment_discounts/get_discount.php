<?php
include_once("connection.php");
session_start();
$number=$_SESSION['number'];
$price=$_SESSION['price'];
echo $discount->get_discount($number,$price);