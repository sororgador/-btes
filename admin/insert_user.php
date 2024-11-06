<?php
include_once("connection.php");
include_once("users.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // استدعاء دالة التحقق والإدخال
    $admin->callvalidateFormInsert($conn);
} else {
    // إذا لم يتم إرسال البيانات بعد، نقوم فقط بعرض النموذج
    $admin->formInsert($conn);
}
?>
