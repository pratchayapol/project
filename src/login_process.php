<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล


// ตรวจสอบว่าค่าจากฟอร์มถูกส่งมาหรือไม่
if (isset($_POST['plate_number']) && isset($_POST['province'])) {
    $plate_number = $_POST['plate_number'];  // กำหนดค่าจากฟอร์ม
    $province = $_POST['province'];        // กำหนดค่าจากฟอร์ม

    // คำสั่ง SQL สำหรับตรวจสอบข้อมูลทะเบียนรถและจังหวัด
    $sql = "SELECT * FROM information_car WHERE plate_number = '$plate_number' AND province = '$province'";

    // ตรวจสอบว่า query ไม่ว่างเปล่า
    if (!empty($sql)) {
        $result = $conn->query($sql);

        // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
        if ($result->num_rows > 0) {
            // ถ้ามีข้อมูลตรง, นำไปหน้าเว็บหลัก
            header("Location: main_page.php");
            exit;
        }
    }
}
// ปิดการเชื่อมต่อ
$conn->close();
?>
