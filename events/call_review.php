<?php

include_once("connection.php");
include_once("class_review.php");
include_once("event.php");
@session_start();


// إضافة التقييمات

///////////////////////
if (isset($_POST['enter'])) //التأكد انه تم الضعط على زر تقييم 
{
    if (isset($_POST['event_id']) && isset($_POST['rating'])) //التأكد انه تم وضع كل من التقييم ورقم الحدث المراد تقييمه في مصفوفة post
    { 
        $eventId = $_POST['event_id'];
        $rating = $_POST['rating'];

        // التحقق من وجود اسم المستخدم في الجلسة
        if (isset($_SESSION['username']))
	{	
            $username = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة
            // استدعاء دالة إضافة التقييم
            $review->addReview($conn, $eventId, $rating, $username);
	   header('Location: /get/events/message.php'); //الانتقال لصفة لطباعة رسالة 		
        } 
	else 
	{
            echo "لم يتم العثور على اسم المستخدم في الجلسة.";
        }
    }   
    else 
    {
        echo "الرجاء اختيار التقييم.";
    }
}//end if

// عرض التقييمات
if (isset($_POST['ok'])) //التأكد انه تم الضغط على زر عرض التقييمات
{
    if (isset($_POST['id'])) //التاكد من وجود رثم الحدث في مصفوفة post
    {
        $eventId = $_POST['id']; //الاحتفاظ برقم الحدث
        $review->displayReviews($conn, $eventId);// استدعاء لدالة لعرض تقييملت الحدث المطلوب
    }
} else 
{
    // عرض الاحدات مع الازرار الخاصة بالتقييم وعرض التقييم
    $event->displayEventWithReviews($conn);
}


?>
 

