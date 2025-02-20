<?php
include('server.php'); // เชื่อมต่อฐานข้อมูล

$errors = array(); // ใช้เก็บข้อผิดพลาด
$status = ""; // สถานะเริ่มต้น

// ตรวจสอบหากมีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']); // รับอีเมลล์

    if (empty($email)) {
        array_push($errors, "กรุณากรอกอีเมล์ของคุณ");
    }

    if (count($errors) == 0) {
        $sql = "SELECT status FROM user_register WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['status'];
        } else {
            array_push($errors, "ไม่พบ Email ของผู้ใช้ในระบบ");
        }

        $stmt->close();
    }
}

$conn->close(); // ปิดการเชื่อมต่อฐานข้อมูล
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบสถานะบัญชี</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
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
<body class="flex items-center justify-center h-screen">
<button id="menu-btn" class="fixed top-4 left-4  bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white p-2 rounded">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>
    <!-- เมนูด้านข้าง -->
    <div id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50">
        <div class="p-4  bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <span class="text-lg font-semibold"style="font-family: 'Kanit', sans-serif;">เมนูนำทาง</span>
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

    <div class="bg-white shadow-lg rounded-lg p-2 sm:p-4 w-full max-w-sm mx-auto m-2 sm:m-4">
        <!-- Logo -->
        <div class="flex justify-center mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/th/e/e0/RMUTI_KORAT.png" 
                 alt="RMUTI Logo" 
                 class="h-16 w-auto rounded-full shadow-md">
        </div>

        <!-- Heading -->
        <h1 class="text-xl sm:text-2xl font-bold text-center text-gray-800 mb-3" style="font-family: 'Kanit', sans-serif;">ตรวจสอบสถานะบัญชี</h1>
        <p class="text-center text-gray-600 text-sm mb-6" style="font-family: 'Kanit', sans-serif;">กรุณากรอกอีเมลของคุณเพื่อดูสถานะบัญชี</p>

        <!-- Form -->
        <form method="POST" action="" class="space-y-3" onsubmit="showLoading()">
            <div>
                <label for="email" class="block text-sm text-gray-700 mb-1" style="font-family: 'Kanit', sans-serif;">
                    อีเมล์:
                </label>
                <input type="email" id="email" name="email" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-sm" style="font-family: 'Kanit', sans-serif;" 
                       placeholder="กรุณากรอกอีเมล@gmail.com" required>
            </div>
            <button type="submit" 
            class="w-full bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300 py-2 px-4 rounded-lg shadow-sm mt-3"
            style="font-family: 'Kanit', sans-serif;">
                ตรวจสอบคำขอ
            </button>
        </form>

        <!-- Loading -->
        <div id="loading" class="hidden text-center text-gray-600 mt-4" style="font-family: 'Kanit', sans-serif;">
            <p>กำลังตรวจสอบสถานะบัญชีของคุณ...</p>
        </div>

        <!-- Errors -->
        <?php if (count($errors) > 0): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mt-4 text-sm" style="font-family: 'Kanit', sans-serif;">
                <ul class="list-disc pl-5 space-y-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Results -->
        <?php if ($status): ?>
            <div class="mt-4">
                <?php if ($status == 'pending'): ?>
                    <p class="text-yellow-600 bg-yellow-100 px-3 py-2 rounded shadow text-sm" style="font-family: 'Kanit', sans-serif;">
                        บัญชีของคุณกำลังรอการอนุมัติ โปรดรอให้ผู้ดูแลระบบอนุมัติการลงทะเบียนของคุณ.
                    </p>
                <?php elseif ($status == 'approved'): ?>
                    <p class="text-green-600 bg-green-100 px-3 py-2 rounded shadow text-sm" style="font-family: 'Kanit', sans-serif;">
                        บัญชีของคุณได้รับการอนุมัติแล้ว คุณสามารถดำเนินการต่อได้เลย!
                    </p>
                    <button onclick="window.location.href='login.php'" 
                            class="mt-3 w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400"
                            style="font-family: 'Kanit', sans-serif;">
                        ดำเนินการต่อ
                    </button>
                <?php else: ?>
                    <p class="text-red-600 bg-red-100 px-3 py-2 rounded shadow text-sm" style="font-family: 'Kanit', sans-serif;">
                    คุณยังไม่ได้ลงทะเบียนหรือมีปัญหากับสถานะบัญชีของคุณ โปรดติดต่อฝ่ายสนับสนุน.
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
        }

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

