<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

$errors = array();

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['email'])) {
    header("Location: loginuser.php");
    exit();
}

$email = $_SESSION['email'];

// ใช้ Prepared Statement เพื่อป้องกัน SQL Injection
$stmt = $conn->prepare("SELECT * FROM paymentinfo WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// SQL Query
$sql = "
    SELECT 
        paymentinfo.first_name, 
        paymentinfo.last_name, 
        paymentinfo.email, 
        paymentinfo.phone_number, 
        paymentinfo.province, 
        information_car.plate_number, 
        information_car.car_type, 
        information_car.brand, 
        information_car.price
    FROM 
        paymentinfo
    JOIN 
        information_car 
        ON paymentinfo.device_user = information_car.device 
    WHERE 
        paymentinfo.email = ?
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Page Popup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.0/dist/sweetalert2.all.min.js"></script>
    <style>
                body{
            font-family: 'Roboto', sans-serif;
        }  
        /* Modal styles */
        #modal {
            display: flex;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Dark overlay */
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 1000px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow-y: auto;
            max-height: 90vh; /* Limit the height */
            position: relative; /* To position the close button */
        }
        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 40px; /* ขนาดกากบาทใหญ่ขึ้น */
            cursor: pointer;
            color: white; /* กากบาทเป็นสีขาว */
            background-color: red; /* พื้นหลังสีแดง */
            border-radius: 50%; /* ให้เป็นรูปวงกลม */
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* เพิ่มเงา */
            transition: background-color 0.3s ease, transform 0.3s ease; /* เพิ่มแอนิเมชัน */
        }
        .modal-close:hover {
            background-color: darkred; /* สีเปลี่ยนเมื่อ hover */
            transform: scale(1.1); /* ขยายขนาดเมื่อ hover */
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">

    <!-- Modal for entire page -->
    <div id="modal">
        <div id="modal-content">
            <span id="closeModal" class="modal-close">&times;</span> <!-- Close button -->

            <?php if ($data): ?>
            <div class="flex flex-col space-y-8">
                <!-- ข้อมูลผู้ใช้ -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300">
                    <h3 class="text-2xl text-gray-700 mb-4 font-sans">ข้อมูลผู้ใช้</h3>
                    <p class="text-lg text-gray-600 mb-2">ชื่อ: 
                        <span class="font-sans"><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">อีเมล: 
                        <span class="font-sans"><?= htmlspecialchars($data['email']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">เบอร์โทร: 
                        <span class="font-sans"><?= htmlspecialchars($data['phone_number']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">จังหวัด: 
                        <span class="font-sans"><?= htmlspecialchars($data['province']) ?></span>
                    </p>
                </div>
            
                <!-- ข้อมูลรถ -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-300">
                    <h3 class="text-2xl text-gray-700 mb-4 font-sans">ข้อมูลรถ</h3>
                    <p class="text-lg text-gray-600 mb-2">ทะเบียนรถ: 
                        <span class="font-sans"><?= htmlspecialchars($data['plate_number']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">ประเภทรถ: 
                        <span class="font-sans"><?= htmlspecialchars($data['car_type']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">ยี่ห้อ: 
                        <span class="font-sans"><?= htmlspecialchars($data['brand']) ?></span>
                    </p>
                    <p class="text-lg text-gray-600 mb-2">ยอดชำระค่าปรับ: 
                        <span class="font-sans"><?= number_format($data['price'], 2) ?> บาท</span>
                    </p>
                </div>
            </div>
            <?php else: ?>
            <div class="text-center text-red-600 text-lg font-medium mt-8">
                <p>ไม่พบข้อมูลของคุณในระบบ</p>
                <p>กรุณาตรวจสอบอีกครั้งหรือติดต่อผู้ดูแลระบบ</p>
            </div>
            <?php endif; ?>

            <script>
                // Close modal when clicking on the close button
                document.getElementById('closeModal').addEventListener('click', function() {
                    window.location.href = 'show_user_db1.php';  // Redirect to the main page
                });
            </script>

        </div>
    </div>
</body>
</html>
