<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

$errors = array();

if (isset($_POST['login_user'])) {
    // รับค่าจากฟอร์มและป้องกัน SQL Injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ตรวจสอบว่าฟิลด์ไม่ว่างเปล่า
    if (empty($email)) {
        array_push($errors, "Email is required");
    }

    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
        $query = "SELECT * FROM user_register WHERE email = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // ตรวจสอบผลลัพธ์
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // ตรวจสอบสถานะ
            if ($row['status'] !== 'approved') {
                $_SESSION['error'] = "Your account is not approved yet.";
                header("Location: login.php");
                exit();
            }

            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $row['password'])) {
                // ตั้งค่าเซสชันและเข้าสู่ระบบ
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role']; // เพิ่ม role ลงในเซสชัน
                $_SESSION['firstname'] = $row['firstname']; // เก็บ firstname ลงในเซสชัน
                $_SESSION['success'] = "You are now logged in";

                // บันทึกประวัติการล็อกอิน
                $user_id = $row['id'];
                $email = $row['email'];

                $insert_query = "INSERT INTO user_login (user_id, email) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_query);

                if (!$stmt) {
                    die("Prepare failed: " . $conn->error);
                }

                $stmt->bind_param("is", $user_id, $email);

                if (!$stmt->execute()) {
                    die("Execution failed: " . $stmt->error);
                }

                // ตรวจสอบบทบาทและเปลี่ยนเส้นทาง
                if ($row['role'] === 'superadmin') {
                    header("Location: super_admin.php");
                } elseif ($row['role'] === 'admin') {
                    header("Location: menu_home.php");
                } else {
                    header("Location: menu_home.php"); // สำหรับ user ทั่วไป
                }
                exit();
            } else {
                $_SESSION['error'] = "อีเมลล์หรือรหัสผ่านไม่ถูกต้อง กรุณากรอกใหม่!";
                header("Location: login.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "กรุณากรอกรหัสใหม่อีกครั้ง!";
            header("Location: login.php");
            exit();
        }
    }
}
?>
