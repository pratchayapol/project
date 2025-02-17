<?php
include 'server.php';

// รับค่าจากฟอร์ม
$id = $_POST['car_id'];
$plate_number = $_POST['plate_number'];
$province = $_POST['province'];
$brand = $_POST['brand'];
$car_type = $_POST['car_type'];
$price = $_POST['price'];

// ตรวจสอบว่าข้อมูลครบถ้วน
if (!empty($id) && !empty($plate_number) && !empty($province) && !empty($brand) && !empty($car_type) && !empty($price)) {
    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE ข้อมูลรถยนต์ 
            SET plate_number = '$plate_number',
                province = '$province',
                brand = '$brand',
                car_type = '$car_type',
                price = '$price'
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // สำเร็จ: กลับไปยังหน้าแสดงข้อมูลและแสดงข้อความ
        header('Location: menu.php');
        exit();
    } else {
        // เกิดข้อผิดพลาด: แสดงข้อความ
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $conn->error;
    }
} else {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน!";
}
?>
