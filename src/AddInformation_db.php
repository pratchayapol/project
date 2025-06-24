<?php 
session_start();
include('server.php');

// ตรวจสอบว่า user_id อยู่ใน session หรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('กรุณาล็อกอินก่อนดำเนินการ');
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT firstname FROM user_register WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($firstname);
    $stmt->fetch();
    $stmt->close();
} else {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลชื่อผู้ใช้");
}

// รับค่าจากฟอร์ม
$plate_number = trim($_POST['plate_number']);
$province = trim($_POST['province']);
$car_type = trim($_POST['car_type']);
$brand = trim($_POST['brand']);
$price = trim($_POST['price']);
$device = trim($_POST['device']);
$unlockcar = trim($_POST['unlockcar']);

// ตรวจสอบว่าไม่มีช่องว่าง
if (empty($plate_number) || empty($province) || empty($car_type) || empty($brand) || empty($price) || empty($device) || empty($unlockcar)) {
    echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบถ้วน');
          </script>";
    exit();
}

// ใช้ prepared statement ป้องกัน SQL Injection
$sql = "INSERT INTO information_car (plate_number, province, car_type, brand, price, device, unlockcar, staff_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param(
        "ssssdssi", 
        $plate_number, 
        $province, 
        $car_type, 
        $brand, 
        $price, 
        $device, 
        $unlockcar, 
        $_SESSION['user_id']
    );

    if ($stmt->execute()) {
        // บันทึกข้อมูลสำเร็จแล้วเปลี่ยนเส้นทางไปยัง menu_home.php
        header("Location: menu_home.php");
        exit(); // ป้องกันการประมวลผลต่อไป
    } else {
        echo "<p style='color: red; text-align: center;'>เกิดข้อผิดพลาด: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color: red; text-align: center;'>เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error . "</p>";
}

$conn->close();
?>
