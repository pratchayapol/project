<?php
include('server.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

$adminEmail = "suphanan.ou@rmuti.ac.th"; // เปลี่ยนเป็นอีเมลของคุณ
$adminPassword = "zhxybaqxyepmnyeh"; // ใช้ App Password ของ Gmail

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['action']) && $_POST['action'] === 'approve') {
        $email = $_POST['email'];

        // อัปเดตสถานะผู้ใช้ในฐานข้อมูล
        $stmt = $conn->prepare("UPDATE user_register SET status = 'approved' WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $stmt->close();

            // ดึงชื่อผู้ใช้จากฐานข้อมูล
            $stmt = $conn->prepare("SELECT firstname FROM user_register WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($firstname);
            $stmt->fetch();
            $stmt->close();

            // ส่งอีเมลแจ้งเตือน
            if (sendApprovalEmail($email, $firstname, $adminEmail, $adminPassword)) {
                $response['status'] = 'success';
                $response['message'] = 'ผู้ใช้ได้รับการอนุมัติและส่งอีเมลสำเร็จ';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'อนุมัติสำเร็จ แต่ส่งอีเมลล้มเหลว';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'เกิดข้อผิดพลาดในการอนุมัติผู้ใช้';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'ข้อมูลไม่ครบถ้วนหรือไม่ถูกต้อง';
    }
}

// ส่งผลลัพธ์ในรูปแบบ JSON
echo json_encode($response);
exit();

// ฟังก์ชันส่งอีเมล
function sendApprovalEmail($email, $firstname, $adminEmail, $adminPassword) {
    $mail = new PHPMailer(true);
    
    try {
        // ตั้งค่า SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $adminEmail;
        $mail->Password = $adminPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ตั้งค่าผู้ส่ง & ผู้รับ
        $mail->setFrom($adminEmail, 'Admin');
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = 'การอนุมัติบัญชีของคุณ'; // หัวข้ออีเมล
        $mail->Body    = "<h3>สวัสดีคุณ $firstname</h3><p>บัญชีของคุณได้รับการอนุมัติแล้ว!</p>"; // เนื้อหาอีเมล

        // ตั้งค่า charset เป็น UTF-8
        $mail->CharSet = 'UTF-8';

        // ส่งอีเมล
        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}
?>