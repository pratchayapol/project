<?php
session_start(); // เริ่มเซสชัน

// ลบข้อมูลทั้งหมดในเซสชัน
session_unset(); 
session_destroy(); 

// เปลี่ยนเส้นทางไปที่หน้า login.php
header('Location: webboard.php');
exit();
?>
