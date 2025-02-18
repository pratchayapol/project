<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า user_id อยู่ใน Session หรือไม่
if (!isset($_SESSION['email'])) {
    echo "คุณยังไม่ได้ล็อกอิน กรุณาเข้าสู่ระบบ";
    exit;
}

// ดึง user_id จาก Session
$user_email = $_SESSION['email'];

// ตรวจสอบว่า email นี้มีอยู่ในฐานข้อมูลหรือไม่
$sql_check = "SELECT id FROM paymentinfo WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $user_email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows === 0) {
    echo "ไม่พบข้อมูลผู้ใช้ในระบบ";
    exit;
}

$stmt_check->bind_result($user_id);
$stmt_check->fetch();
$stmt_check->close();

// SQL Query ที่แก้ไขแล้ว
$sql = "
    SELECT DISTINCT
        paymentinfo.id, 
        paymentinfo.first_name, 
        paymentinfo.last_name, 
        paymentinfo.email, 
        paymentinfo.phone_number, 
        paymentinfo.plate_number, 
        paymentinfo.province, 
        information_car.unlockcar
    FROM 
        paymentinfo
    LEFT JOIN 
        transfer 
        ON paymentinfo.id = transfer.user_id
    LEFT JOIN 
        information_car 
        ON paymentinfo.plate_number = information_car.plate_number
    WHERE 
        transfer.status = 'approved' 
        AND paymentinfo.id = ?
        AND transfer.device_tranfer = information_car.device;
";

// เตรียมคำสั่ง SQL
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // ผูก user_id ใน session กับคำสั่ง SQL
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบและแสดงข้อมูลที่ไม่ซ้ำ
$rows = []; // ตัวแปรสำหรับเก็บข้อมูลที่ไม่ซ้ำ
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // ตรวจสอบว่าข้อมูลนี้เคยเก็บไว้ใน array หรือยัง
        $unique_key = $row['plate_number'] . $row['province']; // ใช้ข้อมูลที่แน่นอนในการเช็คซ้ำ
        if (!isset($rows[$unique_key])) {
            $rows[$unique_key] = $row; // เก็บข้อมูลลงใน array ถ้ายังไม่เคยเก็บ
        }
    }
} else {
    echo "<p>ไม่พบข้อมูล</p>";
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sliding Left Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- เพิ่มลิงก์ SweetAlert2 -->
    <!-- เพิ่มลิงก์ Font Awesome สำหรับไอคอน -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* เมนูด้านซ้าย */
        #left-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            width: 100%;
            max-width: 320px;
            overflow-y: auto;
            height: 100vh;
            z-index: 50;
        }

        #left-menu.open {
            transform: translateX(0);
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563eb',
                        secondary: '#1e40af',
                    },
                },
            },
        };
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 bg-white shadow-lg">
        <div class="p-4 bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white flex justify-between items-center">
            <h2 class=" text-lg font-semibold">เมนู</h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4">
            <ul>
            <li><a href="show_user_db1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="user_information1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบข้อมูล</a></li>
                <li><a href="webhome.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ออกจากระบบชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto px-4 py-6">
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
                <div class="bg-white p-6 rounded-2xl shadow-xl border-2 border-gray-300 mb-6 w-full max-w-2xl mx-auto min-h-[300px]">
                    <p class="text-gray-800 text-lg font-medium">
                        <span class="text-gray-600 ml-4">ป้ายทะเบียน: <?php echo $row['plate_number']; ?></span> 
                        <span class="text-gray-600 ml-4">จังหวัด<?php echo $row['province']; ?></span>
                    </p>
                    <h5 class="text-green-500 mt-2">เจ้าหน้าที่ได้ตรวจสอบหลักฐานการชำระเงินเรียบร้อยแล้ว</h5>

                    <button 
                        class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transform transition-all duration-300" 
                        onclick="showUnlockCode('<?php echo $row['unlockcar']; ?>')">
                        <i class="fas fa-unlock-alt"></i> แสดงรหัสปลดล็อก
                    </button>
                </div>

                <div class="bg-yellow-100 p-6 rounded-2xl shadow-xl border-2 border-gray-300 mb-6 w-full max-w-2xl mx-auto min-h-[100px] border-yellow-500">
                    <div class="flex items-center">
                        <!-- ไอคอนแจ้งเตือน -->
                        <img src="https://www.svgrepo.com/show/500740/warn-triangle-filled.svg" class="h-6 w-6 text-yellow-500 mr-3" alt="Warning Icon"/>
                        <!-- ข้อความแจ้งเตือน -->
                        <h4 class="text-lg font-semibold text-yellow-600">ขั้นตอนการปลดล็อกล้อด้วยตนเอง</h4>
                    </div>
                    <span class="text-gray-700 mt-2">ขั้นตอนการปลดล็อกอุปกรณ์จะแสดงอยู่ที่</span>
                    <span class="mt-4">
                        <a href="show_user_db1.php" class="text-blue-500 hover:underline">หน้าหลัก</a>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-red-500">ไม่พบข้อมูล</p>
        <?php endif; ?>
    </div>

    <script>
        function showUnlockCode(code) {
            Swal.fire({
                title: 'รหัสปลดล็อก',
                html: `<i class="fas fa-unlock-alt fa-3x"></i><br><br>รหัสของคุณคือ: ${code}`, // เพิ่มไอคอน unlock
                icon: 'info',
                confirmButtonText: 'ปิด'
            });
        }
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('menu-toggle');
            const menuClose = document.getElementById('menu-close');
            const leftMenu = document.getElementById('left-menu');

            menuToggle.addEventListener('click', () => {
                leftMenu.classList.add('open');
            });

            menuClose.addEventListener('click', () => {
                leftMenu.classList.remove('open');
            });
        });
    </script>
</body>
</html>
