<?php

include_once("connection.php");
include_once("class_review.php");
include_once("event.php");
@session_start();

if (isset($_POST['enter'])) {
    if (isset($_POST['event_id']) && isset($_POST['rating']) && isset($_POST['comment'])) {
        $eventId = $_POST['event_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        // التأكد من أن الجلسة قد تم بدءها
        
        // التحقق من وجود اسم المستخدم في الجلسة
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة
            
            // استدعاء دالة إضافة التقييم والتعليق
            $review->addReview($conn, $eventId, $rating, $comment, $username);
            header('Location: /get/events/message.php');
        } else {
            echo "لم يتم العثور على اسم المستخدم في الجلسة.";
        }
    } else {
        echo "الرجاء اختيار التقييم وكتابة تعليق.";
    }
}
///////////////////////////////
if (isset($_POST['delete_review'])) {
    if (isset($_POST['review_id'])) {
        $reviewId = $_POST['review_id'];
        
        // التحقق من أن الجلسة قد تم بدءها
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];  // الحصول على اسم المستخدم من الجلسة
            
            // استدعاء دالة حذف التقييم
            $review->deleteReview($conn, $reviewId, $username);
             header('Location: /get/events/message.php');
            // إعادة التوجيه بعد الحذف (اختياري - يمكن تحميل الصفحة من جديد أو إعادة توجيه المستخدم إلى صفحة أخرى)
            
        } else {
            echo "لم يتم العثور على اسم المستخدم في الجلسة.";
        }
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
 

