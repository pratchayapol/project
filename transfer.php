<?php
    // ตรวจสอบการเริ่มต้น session
    session_start();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงินพร้อมแนบหลักฐาน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        // ฟังก์ชันสำหรับแสดงตัวอย่างภาพที่เลือก
        function showPreview(input) {
            var preview = document.getElementById('preview');
            var file = input.files[0];
            
            // ตรวจสอบว่าเป็นไฟล์ภาพ
            if (file && file.type.startsWith('image')) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // แสดงตัวอย่างภาพ
                }
                
                reader.readAsDataURL(file); // อ่านไฟล์ภาพ
            } else {
                preview.style.display = 'none'; // ซ่อนตัวอย่างภาพถ้าไม่ใช่ไฟล์ภาพ
            }
        }

        // ฟังก์ชันสำหรับยกเลิกการอัปโหลด
        function cancelUpload() {
            document.getElementById('fileToUpload').value = ''; // รีเซ็ตฟิลด์ไฟล์
            document.getElementById('preview').style.display = 'none'; // ซ่อนตัวอย่างภาพ
        }
    </script>
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
            <h1 class="text-xl text-center flex-grow"style="font-family: 'Kanit', sans-serif;">ชำระค่าปรับพร้อมแนบหลักฐาน</h1>
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
                <li><a href="user_information.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบข้อมูล</a></li>
                <li><a href="edit.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">แก้ไขข้อมูล</a></li>
                <li><a href="transfer.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ชำระค่าปรับ</a></li>
                <li><a href="webhome.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ออกจากระบบชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>

    <div class="container mx-auto p-8">

        <div class="bg-white shadow-lg rounded-xl p-6 sm:p-8 md:p-12">
            <div class="text-center">
                <h2 class="text-3xl text-gray-800 mb-4"style="font-family: 'Kanit', sans-serif;">ข้อมูลที่ลงทะเบียน</h2>
            </div>

            <?php
            // ตรวจสอบว่ามีข้อมูลใน $_SESSION หรือไม่
            if (isset($_SESSION['first_name'], $_SESSION['last_name'], $_SESSION['email'], $_SESSION['phone_number'], 
                      $_SESSION['plate_number'], $_SESSION['province'], $_SESSION['car_type'], 
                      $_SESSION['brand'], $_SESSION['price'], $_SESSION['device_user'])) {
                echo "<div class='bg-white border border-gray-300 p-4 rounded-lg shadow-md mb-4'>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>ชื่อ: " . htmlspecialchars($_SESSION['first_name']) . " " . htmlspecialchars($_SESSION['last_name']) . "</p>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>อีเมล: " . htmlspecialchars($_SESSION['email']) . "</p>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>เบอร์โทร: " . htmlspecialchars($_SESSION['phone_number']) . "</p>";
                echo "<h2 class='text-xl text-gray-700 mt-6 mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>ข้อมูลรถ</h2>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>ทะเบียนรถ: " . htmlspecialchars($_SESSION['plate_number']) . "</p>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>จังหวัด: " . htmlspecialchars($_SESSION['province']) . "</p>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>ประเภทรถ: " . htmlspecialchars($_SESSION['car_type']) . "</p>";
                echo "<p class='text-lg mb-2 text-center' style='font-family: 'Kanit', sans-serif;'>ยี่ห้อ: " . htmlspecialchars($_SESSION['brand']) . "</p>";
                echo "<p class='text-lg mb-6 text-center' style='font-family: 'Kanit', sans-serif;'>ค่าปรับ: " . htmlspecialchars($_SESSION['price']) . "</p>";
                echo "<p class='text-lg mb-6 text-center' style='font-family: 'Kanit', sans-serif;'>อุปกรณ์ล็อกเครื่องที่:  " . htmlspecialchars($_SESSION['device_user']) . "</p>";
                echo "</div>";
            } else {
                echo "<div class='bg-red-100 border border-red-300 p-4 rounded-lg shadow-md mb-4'>";
                echo "<p class='text-lg text-red-500 text-center'>ไม่มีข้อมูลผู้ลงทะเบียน</p>";
                echo "</div>";
            }
            ?>

            <div class="text-center mt-8">
                <h3 class="text-2xl font-bold text-gray-700"style="font-size: 10px; font-family: 'Kanit', sans-serif;">เลขพร้อมเพย์สำหรับโอนเงิน</h3>
                <img
                    src="https://i.postimg.cc/7hwLF7dR/fb75be15-51eb-4025-8429-7a6d0bfa9f3a-1.png"
                    alt="คู่มือการสมัคร"
                    class="max-w-full"
                />
            </div>

            <!-- ฟอร์มอัปโหลดรูปภาพ -->
            <form action="transfer_db.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" class="space-y-6 mt-8">
                <div class="flex flex-col items-center space-y-4">
                    <label for="fileToUpload" class="text-lg font-medium text-gray-700"style="font-family: 'Kanit', sans-serif;">กรุณาแนบหลักฐานการโอน:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" onchange="showPreview(this)" required class="border border-gray-300 rounded-lg p-3 w-full max-w-sm text-gray-800 focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-center mt-4">
                    <img id="preview" style="display:none; width: 200px; height: auto;" class="rounded-lg shadow-xl" />
                </div>
                
                <div class="flex justify-center mt-6"style="font-family: 'Kanit', sans-serif;">
                    <input type="submit" value="บันทึกรูปภาพ" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 px-8 rounded-full shadow-md hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300">
                </div>
            </form>
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
