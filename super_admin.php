<?php
include('server.php');
// เริ่มต้นเซสชัน
session_start();

// ตัวอย่างการตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['email'])) {
    // หากไม่ได้เข้าสู่ระบบ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
    header("Location: login.php");
    exit();
}

// ตรวจสอบบทบาทของผู้ใช้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    // หากบทบาทไม่ใช่ admin ให้เปลี่ยนเส้นทางไปยังหน้าไม่มีสิทธิ์
    header("Location: webboard.php");
    exit();
}
// ดึงอีเมลจากเซสชัน
$email = $_SESSION['email'];

// คำสั่ง SQL ดึงข้อมูลผู้ใช้
$sql = "SELECT firstname, password_lock FROM user_register WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ตรวจสอบว่าพบข้อมูลผู้ใช้หรือไม่
if (!$user) {
    die("ไม่มีข้อมูลผู้ใช้ในระบบ");
}

// กำหนดตัวแปรจากฐานข้อมูล
$firstname = htmlspecialchars($user['firstname']);
$password_lock = htmlspecialchars($user['password_lock']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sliding Left Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Kanit', sans-serif;
        }  
        /* เมนูด้านซ้าย */
        #left-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            width: 100%; /* ใช้ความกว้างเต็มจอบนมือถือ */
            max-width: 320px; /* กำหนดความกว้างสูงสุดสำหรับเมนู */
            overflow-y: auto; /* เลื่อนแนวตั้งถ้าเนื้อหายาว */
            height: 100vh; /* ให้เมนูยาวเต็มจอ */
            z-index: 50; /* ทำให้เมนูอยู่บนสุด */
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
<body class="bg-gray-100"style="font-family: 'Kanit', sans-serif;">
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex items-center space-x-6">
                <div class="relative group cursor-pointer">
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="profile1.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 bg-white shadow-lg">
        <div class="p-4 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <h2 class=" text-lg"style="font-family: 'Kanit', sans-serif;"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4"style="font-family: 'Kanit', sans-serif;">
            <ul>
                <li><a href="super_check.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบสถานะคำร้องอนุมัติ</a></li>
                <li><a href="super_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลเจ้าหน้าที่ทั้งหมด</a></li>
                <li><a href="manage.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">จัดการผู้ใช้งาน</a></li>
                <li><a href="chack_device.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">เช็คอุปกรณ์</a></li>
                <li><a href="user_sum1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>                
                <li><a href="dashboard1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <p>
                <img src="https://www.svgrepo.com/show/467431/user-5.svg" alt="User Icon" class="inline-block w-6 h-6 mr-2" style="font-family: 'Kanit', sans-serif;" />
                ยินดีต้อนรับหัวหน้าเจ้าหน้าที่ <?php echo htmlspecialchars($firstname); ?>
            </p>
            <p>
                <img src="https://www.svgrepo.com/show/513324/lock.svg" alt="Lock Icon" class="inline-block w-6 h-6 mr-2" style="font-family: 'Kanit', sans-serif;" />
                รหัสเข้าสู่ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับเจ้าหน้าที่: <span class="text-green-500"><?php echo htmlspecialchars($password_lock); ?></span>
            </p>
        </div>
    </div>
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl gradient-heading bordered-heading mb-6"style="font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับเจ้าหน้าที่</h1>
            <p class="text-gray-700 text-lg mb-8" style="font-size: 24px; font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบรับรหัสและล็อกล้อ</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ปุ่มไอคอนข้อมูล -->
                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/504058/button-electricity-charging-plug-energy-power.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">1.กดปุ่มบนอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/61290/wifi-logo.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">2.เชื่อมต่อ wi-fi : ESP32_LOCK</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/503562/scan-qrcode.svg"
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">3.สแกน QRCode บนกล่องอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/476344/login-lock-refresh.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">4.ล็อกอินบนเว็บไซต์ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่าย</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/455949/click.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">5.กดดำเนินการต่อและรับรหัสผ่าน</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/381143/password-account-security-reset-safety.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">6.รับรหัสปลดล็อกอุปกรณ์และนำไปบันทึกในระบบบันทึกข้อมูลรถยนต์</span>
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center justify-center">
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px; font-family: 'Kanit', sans-serif;">QR Code สำหรับเข้าใช้ระบบปลดล็อกอุปกรณ์สำหรับเจ้าหน้าที่แบบสแกนจ่าย</p>
                <img
                    src="https://scontent-atl3-1.xx.fbcdn.net/v/t1.15752-9/472674903_1870284703504796_8326530023000965747_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeHUCagIwpxmNJryeOib0IvcMGKEtS1JS_cwYoS1LUlL99gUPtk_2oum8-b8CWtAW9eQO2-Qcih9FZvz8I4AxoWc&_nc_ohc=4qRRVWz7KnkQ7kNvgGSRcjc&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent-atl3-1.xx&oh=03_Q7cD1gE4-IheKlBWOJTZ2dgZO6cHQ8gPyVr_rY_cwisrCTMH8g&oe=67B05B67"
                    alt="คู่มือการสมัคร"
                    class="max-w-full"
                />
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px; font-family: 'Kanit', sans-serif;">หรือเข้าที่ IP : 192.16.4.1</p>
            </div>
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
    </script>
</body>
</html>
