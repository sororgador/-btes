<?php

include_once("connection.php");
include_once("class_review.php");
include_once("event.php");
@session_start();


// إضافة التقييمات

///////////////////////
if (isset($_POST['enter'])) {
    if (isset($_POST['event_id']) && isset($_POST['rating'])) {
        $eventId = $_POST['event_id'];
        $rating = $_POST['rating'];

        // التأكد من أن الجلسة قد تم بدءها
        
        
        // التحقق من وجود اسم المستخدم في الجلسة
        if (isset($_SESSION['username'])) {
			
            $username = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة
            
            // استدعاء دالة إضافة التقييم
            $review->addReview($conn, $eventId, $rating, $username);
			 header('Location: /get/events/message.php');
			
        } else {
            echo "لم يتم العثور على اسم المستخدم في الجلسة.";
        }
    } else {
        echo "الرجاء اختيار التقييم.";
    }
}

// عرض التقييمات
if (isset($_POST['ok'])) {
    if (isset($_POST['id'])) {
        $eventId = $_POST['id'];
        $review->displayReviews($conn, $eventId);
    }
} else {
    // عرض الأحداث مع الأزرار
    $event->displayEventWithReviews($conn);
}


?>
 

