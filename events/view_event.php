<?php
ob_start(); // بدء التخزين المؤقت

include_once("connection.php");
include_once("class_event.php");

if (isset($_POST['search'])) {
    $event->search($conn);
} else if (isset($_POST['booking'])) {
    header('Location: booking.php'); // استخدم "Location" بحروف كبيرة
    exit; // تأكد من الخروج بعد استخدام header
} else {
    $event->displayformsearch($conn);
}

echo $event->displayEvent($conn);
?>

<html>
<body>
<div class="form-container">
    <form class="form" method="post">
        <button type="submit" name="add" class="btn btn-primary btn-large">Add review</button>
        <button type="submit" name="fav" class="btn btn-secondary btn-large">قائمة المفضلة</button>
     <button type="submit" name="booked" class="btn btn-secondary btn-large">الغاء حجز</button>
    </form>
</div>
</body>
</html>

<?php
if (isset($_POST['add'])) {
    header('Location: login_review.php'); // استخدم "Location" بحروف كبيرة
    exit; // تأكد من الخروج بعد استخدام header
}
if (isset($_POST['fav'])) {
    header('Location: login_fav.php'); // تأكد من عدم وجود ".php" مرتين
    exit; // تأكد من الخروج بعد استخدام header
}
if (isset($_POST['booked'])) {
    header('Location: delete_book.php'); // تأكد من عدم وجود ".php" مرتين
    exit; // تأكد من الخروج بعد استخدام header
}

ob_end_flush(); // إرسال المحتوى المخزن
?>
