<?php
include_once("connection.php");
include_once("users.php");


// استدعاء دالة التحقق والإدخال
$customer->clallvlidatinsert($conn);

// إذا لم يتم الضغط على زر الإرسال، نعرض النموذج
if (!isset($_POST['insert'])) {
    echo $customer->formInsertCustomer($conn);
} 
?>
