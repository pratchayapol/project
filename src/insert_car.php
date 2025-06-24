<?php
session_start();
include('server.php');

// รับข้อมูลจากฟอร์ม
$plate_number = $_POST['plate_number'];
$province = $_POST['province'];
$car_type = $_POST['car_type'];
$brand = $_POST['brand'];
$price = $_POST['price'];
$device = $_POST['device'];
$unlockcar = $_POST['unlockcar'];

// คำสั่ง SQL สำหรับการเพิ่มข้อมูล
$sql = "INSERT INTO ข้อมูลรถยนต์ (plate_number, province, car_type, brand, price, device, unlockcar)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssdsd", $plate_number, $province, $car_type, $brand, $price, $device, $unlockcar);

if ($stmt->execute()) {
    echo "ข้อมูลถูกบันทึกเรียบร้อยแล้ว";
    echo "<a href='view_cars.php'>กลับไปยังหน้าดูข้อมูล</a>";
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
?>
