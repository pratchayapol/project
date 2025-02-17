<?php
session_start();
include('server.php'); // เชื่อมต่อกับฐานข้อมูล

$errors = array();

if (isset($_POST['reg_user'])) {
    // รับค่าจากฟอร์ม
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_2 = trim($_POST['password_2']);

    // ตรวจสอบข้อมูลที่กรอก
    if (empty($firstname)) {
        array_push($errors, "Firstname is required");
    }
    if (empty($lastname)) {
        array_push($errors, "Lastname is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Invalid email format");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
    if ($password != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // ตรวจสอบว่า email มีอยู่ในระบบหรือไม่
    if (count($errors) == 0) {
        $stmt = $conn->prepare("SELECT email FROM user_register WHERE email = ? LIMIT 1");

        if ($stmt === false) {
            die('MySQL Prepare Error: ' . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "อีเมลล์นี้มีผู้ใช้แล้ว กรุณากรอกใหม่อีกครั้ง!";
            header("location: register.php");
            exit();
        }

        $stmt->close();
    }

    // หากไม่มีข้อผิดพลาด ให้บันทึกข้อมูลผู้ใช้
    if (count($errors) == 0) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $password_lock = 'esplockpassword123';

        $stmt = $conn->prepare("INSERT INTO user_register (firstname, lastname, email, password, status, password_lock) VALUES (?, ?, ?, ?, 'pending', ?)");

        if ($stmt === false) {
            die('MySQL Prepare Error: ' . $conn->error);
        }

        $stmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_password, $password_lock);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now registered";
            header('location: approval.php');
            exit();
        } else {
            die("SQL Error: " . $conn->error);
        }
        $stmt->close();
    } else {
        // Displaying errors if any other exist
        $_SESSION['error'] = implode("<br>", $errors);
        header("location: register.php");
        exit();
    }
}
?>
