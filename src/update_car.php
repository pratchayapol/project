<?php
include('server.php');
session_start();  // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['email'])) {
    die("กรุณาล็อกอินก่อนดำเนินการ");
}

// ตรวจสอบว่าได้ส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $plate_number = $_POST['plate_number'];
    $province = $_POST['province'];
    $car_type = $_POST['car_type'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $device = $_POST['device'];
    $unlockcar = $_POST['unlockcar'];

    // แสดงค่าที่ได้รับจากฟอร์ม
    echo "Plate number: " . $plate_number . "<br>";
    echo "Province: " . $province . "<br>";
    echo "Car type: " . $car_type . "<br>";
    echo "Brand: " . $brand . "<br>";
    echo "Price: " . $price . "<br>";
    echo "Device: " . $device . "<br>";
    echo "Unlockcar: " . $unlockcar . "<br>";
    echo "User ID: " . $user_id . "<br>";

    // ตรวจสอบค่าที่ได้รับ
    if (empty($plate_number) || empty($province) || empty($car_type) || empty($brand) || empty($price) || empty($device)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit;
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE information_car SET plate_number = ?, province = ?, car_type = ?, brand = ?, price = ?, device = ?, unlockcar = ? WHERE user_id = ?";
    
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);

    // ตรวจสอบการเตรียมคำสั่ง SQL
    if ($stmt === false) {
        die('เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: ' . $conn->error);
    }

    // ผูกค่าตัวแปรกับพารามิเตอร์ในคำสั่ง SQL
    $stmt->bind_param("ssssdsdi", $plate_number, $province, $car_type, $brand, $price, $device, $unlockcar, $user_id);

    // ตรวจสอบการทำงานของ execute()
    if ($stmt->execute()) {
        // ส่งกลับไปยังหน้าหลักหลังจากอัปเดต
        header('Location: edit_car.php'); // เปลี่ยนเป็นหน้าที่คุณต้องการรีไดเรกต์ไปหลังการอัปเดต
        exit;
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
    }
}
?>
