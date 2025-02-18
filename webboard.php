<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แถบนำทางแบบด้านข้าง</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
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
            <span class="text-lg font-semibold">เมนูนำทาง</span>
            <button id="close-btn" class="text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <ul class="mt-4">
            <li><a href="login.php" class="block px-4 py-2 hover:bg-blue-100 menu-item">เข้าสู่ระบบ</a></li>
            <li><a href="register.php" class="block px-4 py-2 hover:bg-blue-100 menu-item">สมัครเข้าสู่ระบบ</a></li>
            <li><a href="approval.php" class="block px-4 py-2 hover:bg-blue-100 menu-item">ตรวจสอบสถานะ</a></li>
        </ul>
    </div>

    <!-- เนื้อหาหลัก -->
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl gradient-heading bordered-heading mb-6">คู่มือการเข้าใช้ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับเจ้าหน้าที่</h1>
            <p class="text-gray-700 text-lg mb-8" style="font-size: 24px;">คู่มือการเข้าใช้ระบบรับรหัสและล็อกล้อ</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ปุ่มไอคอนข้อมูล -->
                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/504058/button-electricity-charging-plug-energy-power.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">1.กดปุ่มบนอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/61290/wifi-logo.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">2.เชื่อมต่อ wi-fi : ESP32_LOCK</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/503562/scan-qrcode.svg"
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">3.สแกน QRCode บนกล่องอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/476344/login-lock-refresh.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">4.ล็อกอินบนเว็บไซต์ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่าย</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/455949/click.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">5.กดดำเนินการต่อและรับรหัสผ่าน</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/381143/password-account-security-reset-safety.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem;">6.รับรหัสปลดล็อกอุปกรณ์และนำไปบันทึกในระบบบันทึกข้อมูลรถยนต์</span>
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center justify-center">
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px;">QR Code สำหรับเข้าใช้ระบบปลดล็อกอุปกรณ์สำหรับเจ้าหน้าที่แบบสแกนจ่าย</p>
                <img
                    src="https://scontent-atl3-1.xx.fbcdn.net/v/t1.15752-9/472674903_1870284703504796_8326530023000965747_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=0024fc&_nc_eui2=AeHUCagIwpxmNJryeOib0IvcMGKEtS1JS_cwYoS1LUlL99gUPtk_2oum8-b8CWtAW9eQO2-Qcih9FZvz8I4AxoWc&_nc_ohc=4qRRVWz7KnkQ7kNvgGSRcjc&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent-atl3-1.xx&oh=03_Q7cD1gE4-IheKlBWOJTZ2dgZO6cHQ8gPyVr_rY_cwisrCTMH8g&oe=67B05B67"
                    alt="คู่มือการสมัคร"
                    class="max-w-full"
                />
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px;">หรือเข้าที่ IP : 192.16.4.1</p>
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

