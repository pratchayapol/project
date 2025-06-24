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
            <h2 class=" text-lg"style="font-family: 'Kanit', sans-serif;"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4"style="font-family: 'Kanit', sans-serif;">
            <ul>
                <li><a href="user_information1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
                <li><a href="sum_unlock.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบรหัสปลดล็อก</a></li>
                <li><a href="webhome.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ออกจากระบบชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl gradient-heading bordered-heading mb-6"style="font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับผู้ชำระค่าปรับ</h1>
            <p class="text-gray-700 text-lg mb-8" style="font-size: 24px; font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบสแกนจ่ายและระบบกรอกรหัสล็อกล้อ</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/503116/unlocked.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">4.หลักฐานถูกต้องจะได้รับรหัสปลดล็อกอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/513070/wifi-1029.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">6.เชื่อมต่อ WIFI ที่ชื่อ "ESP32_LOCK"</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/257107/worldwide-internet.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">6.เข้าสู่ระบบด้วยเลข IP : 192.16.4.1</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/478283/web-page.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">7.เมื่อเข้าสู่เว็บไซต์ให้เลือก "ผู้ชำระค่าปรับ"</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/501413/unlock.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">8.กรอกรหัสที่ได้รับเพื่อปลดล็อกอุปกรณ์</span>
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center justify-center">
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px;">QR Code สำหรับเข้าใช้ระบบปกรอกรหัสเพื่อปลดล็อกอุปกรณ์</p>
                <img
                    src="https://i.postimg.cc/kg9XtKn4/472674903-1870284703504796-8326530023000965747-n-2.jpg"
                    alt="คู่มือการสมัคร"
                    class="max-w-full"
                />
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px; font-family: 'Kanit', sans-serif;">หรือเข้าที่ IP : 192.16.4.1</p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuBtn = document.getElementById('menu-btn');
        const closeBtn = document.getElementById('close-btn');
        const sidebar = document.getElementById('sidebar');

        // กดปุ่มเมนูเพื่อแสดงเมนูด้านข้าง
        menuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
        });

        // กดปุ่มปิดเมนูเพื่อซ่อนเมนูด้านข้าง
        closeBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
        });

        // ปิดเมนูหากกดพื้นที่ภายนอกเมนู
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !menuBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    });

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

