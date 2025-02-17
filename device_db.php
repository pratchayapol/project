<?php
    include('server.php'); // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลอุปกรณ์จากฐานข้อมูล
$sql = "SELECT * FROM information_car";  // คำสั่ง SQL
$result = $conn->query($sql);

// ส่งข้อมูลออกเป็น JSON
$devices = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }
} 

// ส่งข้อมูล JSON
echo json_encode($devices);

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
