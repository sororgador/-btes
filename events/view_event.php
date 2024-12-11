<html>
<body>
<div class="form-container">
    <form class="form" method="post">
        <button type="submit" name="add" class="btn btn-primary btn-large">Add review</button>
        <button type="submit" name="fav" class="btn btn-secondary btn-large">قائمة المفضلة</button>
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

ob_end_flush(); // إرسال المحتوى المخزن
?>
