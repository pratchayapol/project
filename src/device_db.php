<?php
    include('server.php'); // เชื่อมต่อฐานข้อมูล

    $date_filter = "";
    $name_filter = "";
    $email_filter = "";

    // กรองวันที่
    if (isset($_POST['searchDate']) && !empty($_POST['searchDate'])) {
        $search_date = $conn->real_escape_string($_POST['searchDate']);
        $date_filter = "WHERE DATE(created_at) = '$search_date'";
    }

    // กรองชื่อ
    if (isset($_POST['searchName']) && !empty($_POST['searchName'])) {
        $search_name = $conn->real_escape_string($_POST['searchName']);
        if ($date_filter == "") {
            $name_filter = "WHERE province LIKE '%$search_name%' OR brand LIKE '%$search_name%'";
        } else {
            $name_filter = "AND (province LIKE '%$search_name%' OR brand LIKE '%$search_name%')";
        }
    }

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
