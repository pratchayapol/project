<?php
session_start(); // เริ่มต้น session

include('server.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileToUpload'])) {
    $file = $_FILES['fileToUpload'];
    $file_name = basename($file['name']);
    $file_tmp = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    // ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        
        // ดึง user_id จาก email ใน paymentinfo
        $sql = "SELECT id FROM paymentinfo WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        if (!$user_id) {
            die("ไม่พบ user_id สำหรับ email นี้ในระบบ");
        }
    } else {
        die("กรุณาล็อกอินก่อนทำการอัปโหลดไฟล์");
    }

    // ตั้งค่าที่เก็บไฟล์ (โฟลเดอร์ uploads)
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    // ตั้งชื่อไฟล์ใหม่ให้ไม่ซ้ำ
    $unique_name = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9\._-]/", "", $file_name);
    $target_file = $target_dir . $unique_name;

    // ตรวจสอบการอัปโหลดไฟล์
    if ($file_error === UPLOAD_ERR_OK) {
        // ตรวจสอบประเภทไฟล์
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, $allowed_types)) {
            // ตรวจสอบขนาดไฟล์ (ไม่เกิน 2MB)
            if ($file_size <= 2 * 1024 * 1024) {
                // ย้ายไฟล์ไปยังโฟลเดอร์เป้าหมาย
                if (move_uploaded_file($file_tmp, $target_file)) {
                    // เริ่มต้นการตรวจสอบสลิปผ่าน API
                    $apiKey = "SLIPOKYGR4W10"; // ใส่ API Key ของคุณ
                    $apiUrl = "https://api.slipok.com/api/line/apikey/34802";

                    $headers = [
                        'Content-Type: multipart/form-data',
                        'x-authorization: ' . $apiKey
                    ];

                    $fields = [
                        'files' => new CURLFile($target_file)
                    ];

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($ch);
                    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    if ($response === false) {
                        die("เกิดข้อผิดพลาดจาก cURL: " . curl_error($ch));
                    }

                    curl_close($ch);

                    // ตรวจสอบสถานะ HTTP
                    if ($http_code !== 200) {
                        die("API Error: HTTP Code $http_code. Response: " . htmlspecialchars($response));
                    }

                    // ตรวจสอบ JSON
                    $result = json_decode($response, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        die("JSON Decode Error: " . json_last_error_msg() . ". Response: " . htmlspecialchars($response));
                    }

                    // ตรวจสอบสถานะการตอบกลับ
                    if (isset($result['success']) && $result['success'] === true) {
                        // ดึงข้อมูลจาก API
                        $data = $result['data'];
                        $transaction_date = isset($data['transDate']) ? $data['transDate'] : 'ไม่ระบุ';
                        $transaction_time = isset($data['transTime']) ? $data['transTime'] : 'ไม่ระบุ';
                        $bank_name = isset($data['sendingBank']) ? $data['sendingBank'] : 'ไม่ระบุ';
                        $amount = isset($data['amount']) ? (int)$data['amount'] : 0; // ตรวจสอบจำนวนเงินเป็นจำนวนเต็ม
                    
                        // ตรวจสอบจำนวนเงิน
                        if ($amount == 2) {
                            // กำหนดค่าปัจจุบันให้กับ created_at
                            $created_at = date('Y-m-d H:i:s');
                    
                            // กำหนดค่าคอลัมน์ status
                            $status = 'approved'; // กำหนดสถานะเป็น "สำเร็จ"
                    
                            // สมมติว่า device_user มาจากเซสชั่น
                            $device_user = isset($_SESSION['device_user']) ? $_SESSION['device_user'] : 'ไม่ระบุ'; 
                    
                            // เตรียมคำสั่ง SQL
                            $sql = "INSERT INTO transfer (user_id, image_name, image_path, status, amount, created_at, device_tranfer) VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                    
                            if ($stmt) {
                                // เพิ่มค่า device_tranfer เป็นค่า device_user
                                $stmt->bind_param("isssssi", $user_id, $unique_name, $target_file, $status, $amount, $created_at, $device_user);
                    
                                if ($stmt->execute()) {
                                    header("Location: show_unlock.php");
                                    exit();
                                } else {
                                    die("เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . htmlspecialchars($stmt->error));
                                }
                    
                                $stmt->close();
                            } else {
                                die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . htmlspecialchars($conn->error));
                            }
                        } else {
                            // หากยอดเงินไม่ถูกต้องให้แสดงป็อปอัพแจ้งเตือน
                            echo "
                            <!DOCTYPE html>
                            <html lang='en'>
                            <head>
                                <meta charset='UTF-8'>
                                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                            </head>
                            <body>
                                <script>
                                    Swal.fire({
                                        title: 'ข้อผิดพลาด!',
                                        text: 'ยอดเงินที่โอนมาจากสลิปไม่ถูกต้อง กรุณาติดต่อเจ้าหน้าที่ได้ที่เบอร์ 065-625-3502',
                                        icon: 'error',
                                        confirmButtonText: 'ตกลง'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = 'transfer.php';
                                        }
                                    });
                                </script>
                            </body>
                            </html>
                            ";
                            exit();
                        }
                    } else {
                        $error_message = isset($result['message']) ? $result['message'] : "ไม่ทราบข้อผิดพลาด";
                        die("การตรวจสอบสลิปล้มเหลว: " . htmlspecialchars($error_message));
                    }
                } else {
                    die("ไม่สามารถย้ายไฟล์ไปยังโฟลเดอร์เป้าหมายได้");
                }
            } else {
                die("ไฟล์มีขนาดใหญ่เกินไป (จำกัดไม่เกิน 2MB)");
            }
        } else {
            die("ประเภทไฟล์ไม่รองรับ (รองรับเฉพาะ JPG, PNG, GIF, WEBP)");
        }
    } else {
        die("เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $file_error);
    }
} else {
    die("กรุณาเลือกไฟล์เพื่ออัปโหลด");
}

$conn->close();
?>

