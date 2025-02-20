<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

$errors = array();

if (isset($_POST['submit_payment'])) {
    // รับค่าจากฟอร์มและป้องกัน SQL Injection
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $plate_number = mysqli_real_escape_string($conn, $_POST['plate_number']);
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $device_user = (int) $_POST['device_user']; // แปลงเป็น integer

    // ตรวจสอบว่าฟิลด์ไม่ว่างเปล่า
    if (empty($first_name)) {
        array_push($errors, "First name is required");
    }
    if (empty($last_name)) {
        array_push($errors, "Last name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($phone_number)) {
        array_push($errors, "Phone number is required");
    }
    if (empty($plate_number)) {
        array_push($errors, "Plate number is required");
    }
    if (empty($province)) {
        array_push($errors, "Province is required");
    }
    if (empty($device_user)) {
        array_push($errors, "Device user is required");
    }

    if (count($errors) == 0) {
        // ไม่ต้องตรวจสอบอีเมลซ้ำในฐานข้อมูล

        // ตรวจสอบทะเบียนรถและจังหวัดในฐานข้อมูล
        $query = "SELECT * FROM information_car WHERE plate_number = ? AND province = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $plate_number, $province);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // บันทึกข้อมูลผู้ชำระเงิน
            $insert_query = "INSERT INTO paymentinfo (first_name, last_name, email, phone_number, plate_number, province, device_user) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);

            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("ssssssi", $first_name, $last_name, $email, $phone_number, $plate_number, $province, $device_user);

            if ($stmt->execute()) {
                // เก็บข้อมูลลงใน Session
                $_SESSION['user_id'] = $conn->insert_id; 
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;
                $_SESSION['phone_number'] = $phone_number;
                $_SESSION['plate_number'] = $row['plate_number'];
                $_SESSION['province'] = $row['province'];
                $_SESSION['car_type'] = $row['car_type'];
                $_SESSION['brand'] = $row['brand'];
                $_SESSION['price'] = $row['price'];
                $_SESSION['device_user'] = $device_user;  // เก็บค่า device_user ลงใน session

                // เปลี่ยนเส้นทางไปหน้าแสดงผล
                header("Location: show_user_db.php");
                exit();
            } else {
                die("Execution failed: " . $stmt->error);
            }
        } else {
            $_SESSION['error'] = "ไม่พบข้อมูลทะเบียนรถหรือจังหวัดที่ตรงกัน";
            header("Location: loginuser.php");
            exit();
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
        header("Location: loginuser.php");
        exit();
    }
}
?>
