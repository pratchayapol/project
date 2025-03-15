<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>;วิธีใช้งานระบบสำหรับผู้ชำระ</title>
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
<body class="bg-gray-100" style="font-family: 'Kanit', sans-serif;">
    <header class="bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <a href="loginuser.php" 
            class="flex items-center px-4 py-2 bg-transparent text-white text-lg font-semibold rounded-lg hover:text-[#E6E6FA] transition duration-300"style="font-family: 'Kanit', sans-serif;">
                <img src="https://www.svgrepo.com/show/500925/login.svg" 
                    alt="Register Icon" 
                    class="w-6 h-6 mr-2 invert">
                ลงทะเบียนชำระค่าปรับ
            </a>
        </div>
    </header>
    <!-- เนื้อหาหลัก -->
    <div class="container mx-auto mt-12 px-6">
        <div class="bg-white shadow-md rounded-lg p-8">
            <h1 class="text-3xl gradient-heading bordered-heading mb-6"style="font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบอุปกรณ์ล็อกล้อแบบสแกนจ่ายสำหรับผู้ชำระค่าปรับ
            </h1>
            <p class="text-gray-700 text-lg mb-8" style="font-size: 24px; font-family: 'Kanit', sans-serif;">วิธีการเข้าใช้ระบบสแกนจ่ายและระบบกรอกรหัสล็อกล้อ</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ปุ่มไอคอนข้อมูล -->
                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/374676/form.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4"
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">1.ลงทะเบียนเข้าใช้ระบบด้วยข้อมูลทั่วไปและข้อมูลทะเบียนรถ
                        <a href="https://lockingdevice.pcnone.com/loginuser.php" target="_blank" rel="noopener noreferrer">
                            คลิกที่นี่!
                        </a>
                    </span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/94170/form.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">2.ตรวจสอบข้อมูลของท่านให้เรียบร้อย</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/261309/transfer-account.svg"
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">3.ชำระค่าปรับพร้อมแนบหลักฐานการโอนเงินชำระ</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/503116/unlocked.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">4.หลักฐานถูกต้องจะได้รับรหัสปลดล็อกอุปกรณ์</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/513070/wifi-1029.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">6.เชื่อมต่อ WIFI ที่ชื่อ "ESP32_LOCK"</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/257107/worldwide-internet.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">6.เข้าสู่ระบบด้วยเลข IP : 192.16.4.1</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/478283/web-page.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">7.เมื่อเข้าสู่เว็บไซต์ให้เลือก "ผู้ชำระค่าปรับ"</span>
                </button>

                <button class="flex flex-col items-center bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    <img src="https://www.svgrepo.com/show/501413/unlock.svg" 
                    alt="Icon Information" 
                    class="w-16 h-16 mb-4 mt-4" 
                    style="filter: brightness(0) invert(1);">
                    <span class="text-lg font-medium text-white" style="font-size: 1rem; font-family: 'Kanit', sans-serif;">8.กรอกรหัสที่ได้รับเพื่อปลดล็อกอุปกรณ์</span>
                </button>
            </div>
            <div class="bg-white shadow-md rounded-lg p-8 flex flex-col items-center justify-center">
                <p class="text-gray-700 text-lg mb-8 text-center" style="font-size: 24px; font-family: 'Kanit', sans-serif;">QR Code สำหรับเข้าใช้ระบบปกรอกรหัสเพื่อปลดล็อกอุปกรณ์</p>
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
</script>


</body>
</html>

