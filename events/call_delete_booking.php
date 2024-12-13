<?php
session_start();
include_once("connection.php");
include_once("class_booking.php");

if (isset($_POST['delete_booking'])) {
  
        $cancelResult = $booking->cancelBooking($conn);
}
        
        else {
            $booking->viewBooking($conn); // عرض الحجوزات المتاحة مرة أخرى
        }
    

