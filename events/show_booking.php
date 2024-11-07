<?php
include_once("connection.php");
include_once("class_booking.php");
 
 echo $booking->createBooking($conn);
  echo $booking->showBooking($conn);
 