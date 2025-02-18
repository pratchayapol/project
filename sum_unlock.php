<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่า email อยู่ใน Session หรือไม่
if (!isset($_SESSION['email'])) {
    echo "คุณยังไม่ได้ล็อกอิน กรุณาเข้าสู่ระบบ";
    exit;
}

// ดึง email จาก Session
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

// ดึงเฉพาะข้อมูล unlockcar
$sql = "
    SELECT DISTINCT information_car.unlockcar
    FROM paymentinfo
    LEFT JOIN transfer ON paymentinfo.id = transfer.user_id
    LEFT JOIN information_car ON paymentinfo.plate_number = information_car.plate_number
    WHERE transfer.status = 'approved' 
    AND paymentinfo.id = ?
    AND transfer.device_tranfer = information_car.device;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีข้อมูลหรือไม่
$unlock_codes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unlock_codes[] = $row['unlockcar'];
    }
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }       
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
            <h1 class="text-xl font-sans text-center flex-grow">รหัสปลดล็อกอุปกรณ์ล็อกล้อ</h1>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 bg-white shadow-lg">
        <div class="p-4 bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white flex justify-between items-center">
            <h2 class=" text-lg font-sans">เมนู</h2>
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
        <div class="bg-white p-8 rounded-2xl shadow-xl border-2 border-gray-300 mt-8 w-full max-w-5xl mx-auto min-h-[200px]">
        <div style="text-align: center; margin-top: 50px;">
            <?php if (!empty($unlock_codes)): ?>
                <?php foreach ($unlock_codes as $code): ?>
                    <p style="font-size: 24px; font-weight: bold; color: green;">รหัสปลดล็อกของคุณคือ: <?php echo $code; ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: red;">ไม่พบรหัสปลดล็อก</p>
            <?php endif; ?>
            <button type="button" onclick="copyAndOpen()" class="w-full sm:w-auto px-10 py-1 bg-indigo-500 text-white rounded-md shadow-sm">
                คัดลอก
            </button>
        </div>
        <div class="mt-8 bg-red-100 text-red-700 p-4 rounded-lg shadow-md flex items-center">
            <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
            <span class="font-bold">หากพบการจอดรถผิดกฎหมายเกิน 5 ครั้ง จะมีมาตรการลงโทษที่เข้มงวดมากขึ้น รวมถึงค่าปรับที่สูงขึ้นอย่างมาก.</span>
        </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-xl border-2 border-gray-300 mt-8 w-full max-w-5xl mx-auto min-h-[200px]">
            <h1 class="text-2xl font-bold text-red-600">🚨 โปรดจอดรถให้ถูกต้อง! 🚨</h1>
                <p class="text-lg text-gray-700 mt-4">การจอดรถผิดกฎจราจรอาจทำให้เกิดอุบัติเหตุ ขัดขวางการจราจร และมีผลกระทบต่อผู้อื่น กรุณาเคารพกฎจราจรเพื่อความปลอดภัยของทุกคน</p>
                    <ul class="text-left text-gray-700 mt-4 space-y-2">
                        <li>✅ ปฏิบัติตามเครื่องหมายและป้ายจราจร</li>
                        <li>✅ หลีกเลี่ยงการจอดขวางทางเดินรถ</li>
                        <li>✅ ให้ความสำคัญกับทางคนข้าม</li>
                        <li>✅ ไม่จอดในที่ห้ามจอดเด็ดขาด</li>
                    </ul>
        </div>
    </div>
    <script>
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
        function copyAndOpen() {
                // คัดลอกตัวเลขจากช่อง input
                const code = document.getElementById('unlockcar');
                code.select();
                document.execCommand('copy');

                // แสดงป๊อปอัพ SweetAlert เมื่อคัดลอกสำเร็จ
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกสำเร็จ',
                    text: 'รหัสปลดล็อกถูกคัดลอกไปยังคลิปบอร์ดแล้ว!',
                    confirmButtonText: 'ตกลง'
                });
            }
    </script>
</body>
</html>
