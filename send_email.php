<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include("server.php");

// ตรวจสอบการเชื่อมต่อ
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// ตรวจสอบว่าได้รับค่า id และเป็นตัวเลข
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("ID ไม่ถูกต้อง");
}

$id = $_GET['id'];

// ตรวจสอบว่า ID นี้ได้รับการอนุมัติแล้วหรือไม่
$sql = "SELECT email, firstname FROM user_register WHERE id = ? AND status = 'approved'";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($admin = $result->fetch_assoc()) {
    // ส่งอีเมลไปยังผู้ใช้ที่ได้รับการอนุมัติ
    if (sendApprovalEmail($admin['email'], $admin['firstname'])) {
        echo "อีเมลแจ้งเตือนถูกส่งไปยัง " . $admin['email'];
    } else {
        echo "ไม่สามารถส่งอีเมลได้";
    }
} else {
    echo "ID นี้ยังไม่ได้รับการอนุมัติ หรือไม่มีอยู่ในระบบ";
}

$mysqli->close();

// ฟังก์ชันส่งอีเมล
function sendApprovalEmail($adminEmail, $adminName) {
    $mail = new PHPMailer(true);

    try {
        // ตั้งค่า SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'superadmin@email.com'; 
        $mail->Password = 'uwtibpxhhsggqmmb'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ตั้งค่าอีเมล
        $mail->setFrom('your_email@gmail.com', 'Superadmin');
        $mail->addAddress($adminEmail, $adminName);

        // เนื้อหาของอีเมล
        $mail->isHTML(true);
        $mail->Subject = 'บัญชีของคุณได้รับการอนุมัติแล้ว';
        $mail->Body    = "<h3>สวัสดีคุณ $adminName,</h3><p>บัญชีของคุณได้รับการอนุมัติแล้ว คุณสามารถเข้าสู่ระบบได้ทันที</p>";

        // ส่งอีเมล
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("ไม่สามารถส่งอีเมลได้: {$mail->ErrorInfo}");
        return false;
    }
}
?>
