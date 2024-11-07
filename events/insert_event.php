<?php
include_once("connection.php");
include_once("event.php");

// استدعاء دالة التحقق من المدخلات وإضافة البيانات إلى قاعدة البيانات
$event->callvalidationdisplay_form($conn);
 