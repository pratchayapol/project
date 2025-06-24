<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิธีการใช้งานระบบปลดล็อกล้อสำหรับเจ้าหน้าที่</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Kanit', sans-serif;
        }
        /* กำหนดเมนูด้านข้าง */
        #sidebar {
            transform: translateX(-100%); /* ซ่อนเมนูในเริ่มต้น */
            transition: transform 0.3s ease;
        }
        #sidebar.active {
            transform: translateX(0); /* แสดงเมนูเมื่อ active */
        }

        /* กำหนดขนาดตัวหนังสือ */
        body {
            font-size: 16px; /* กำหนดขนาดตัวหนังสือพื้นฐาน */
        }

        h1 {
            font-size: 2rem; /* ขนาดตัวหนังสือสำหรับหัวเรื่อง */
        }

        p, a, span {
            font-size: 1rem; /* ขนาดตัวหนังสือทั่วไป */
        }

        .menu-item {
            font-size: 1.25rem; /* ขนาดตัวหนังสือสำหรับเมนู */
        }
    </style>
</head>
<body class="bg-gradient-to-r from-[#C9DFEC] to-[#C6DEFF] min-h-screen flex items-center justify-center">
    <!-- ปุ่มสามขีด -->
    <button id="menu-btn" class="fixed top-4 left-4  bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white p-2 rounded">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

    <!-- เมนูด้านข้าง -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50">
        <div class="p-4  bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <span class="text-lg" style="font-family: 'Kanit', sans-serif;">เมนูนำทาง</span>
            <button id="close-btn" class="text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <ul class="mt-4">
            <li><a href="login.php" class="block px-4 py-2 hover:bg-blue-100 menu-item" style="font-family: 'Kanit', sans-serif;">เข้าสู่ระบบ</a></li>
            <li><a href="register.php" class="block px-4 py-2 hover:bg-blue-100 menu-item" style="font-family: 'Kanit', sans-serif;">สมัครเข้าสู่ระบบ</a></li>
            <li><a href="approval.php" class="block px-4 py-2 hover:bg-blue-100 menu-item" style="font-family: 'Kanit', sans-serif;">ตรวจสอบสถานะ</a></li>
        </ul>
    </div>

    <!-- เนื้อหาหลัก -->
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl gradient-heading bordered-heading mb-6" style="font-family: 'Kanit', sans-serif;">คู่มือการเข้าใช้ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับเจ้าหน้าที่</h1>
            <p class="text-gray-700 text-lg mb-8" style="font-size: 24px;" style="font-family: 'Kanit', sans-serif;">คู่มือการเข้าใช้ระบบรับรหัสและล็อกล้อ</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ปุ่มไอคอนข้อมูล -->
                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/504058/button-electricity-charging-plug-energy-power.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">1.กดปุ่มบนอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/61290/wifi-logo.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">2.เชื่อมต่อ wi-fi : ESP32_LOCK</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/503562/scan-qrcode.svg"
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">3.สแกน QRCode บนกล่องอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/476344/login-lock-refresh.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">4.ล็อกอินบนเว็บไซต์ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่าย</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/455949/click.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">5.กดดำเนินการต่อและรับรหัสผ่าน</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300"
                    style="font-family: 'Kanit', sans-serif;">
                    <img src="https://www.svgrepo.com/show/381143/password-account-security-reset-safety.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg text-white" style="font-size: 1rem;" style="font-family: 'Kanit', sans-serif;">6.รับรหัสปลดล็อกอุปกรณ์และนำไปบันทึกในระบบบันทึกข้อมูลรถยนต์</span>
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center justify-center">
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px;" style="font-family: 'Kanit', sans-serif;">QR Code สำหรับเข้าใช้ระบบปลดล็อกอุปกรณ์สำหรับเจ้าหน้าที่แบบสแกนจ่าย</p>
                <img src="https://i.postimg.cc/kg9XtKn4/472674903-1870284703504796-8326530023000965747-n-2.jpg" 
                    alt="รูปจาก PostImages" 
                    class="max-w-full">
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
</script>


</body>
</html>

