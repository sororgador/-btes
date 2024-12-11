<?php
session_start();
include_once("connection.php"); // تأكد من تضمين الاتصال بقاعدة البيانات

if (isset($_POST['titles']) && is_array($_POST['titles'])) {
    $titles = $_POST['titles'];
    $username = $_SESSION['username'];

    try {
        foreach ($titles as $title) {
            // استعلام لإضافة العنوان إلى جدول المفضلة
            $sql = "INSERT INTO favorites (username, title) VALUES (:username, :title)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':title', $title);
            $stmt->execute();
        }
        echo "تمت إضافة المفضلات بنجاح!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "لم يتم اختيار أي عنوان.";
}
?>