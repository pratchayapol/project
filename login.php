<?php  
    include('server.php');
    session_start();            
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.src = 'https://www.svgrepo.com/show/521139/eye-show.svg'; // ไอคอนเปิดตา
            } else {
                passwordField.type = 'password';
                eyeIcon.src = 'https://www.svgrepo.com/show/524041/eye-closed.svg'; // ไอคอนปิดตา
            }
        }
    </script>
</head>
<body class="flex items-center justify-center h-screen">

    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-center mb-6">
            <img src="https://upload.wikimedia.org/wikipedia/th/e/e0/RMUTI_KORAT.png" alt="Logo" class="w-20 h-auto">
        </div>
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6" style="font-size: 16px;">ยินดีต้อนรับเข้าสู่เว็บไซต์สำหรับเจ้าหน้าที่</h2>
        
        <form action="login_db.php" method="post">
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="bg-red-100 text-red-700 border border-red-400 rounded-md p-4 mb-4">
                    <h3>
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="text" id="email" name="email" placeholder="กรุณากรอกอีเมลของท่าน" required 
                       class="w-full mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="กรุณากรอกรหัสผ่านของท่าน" required 
                           class="w-full mt-1 px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-10">
                    <span onclick="togglePasswordVisibility()" 
                          class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                        <img id="eye-icon" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                             alt="Toggle visibility" class="w-5 h-5">
                    </span>
                </div>
            </div>

            <div class="text-sm text-gray-600 mt-3">
                <p>หากยังไม่ได้ยืนยันอีเมลล์<a href="approval.php" class="text-blue-500 hover:underline">  ตรวจสอบสถานะ</a></p>
            </div>
            
            <button type="submit" name="login_user" 
                    class="w-full bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300 font-medium py-2 px-4 rounded-lg shadow-sm mt-3">
                เข้าสู่ระบบ
            </button>
            
            <div class="text-sm text-gray-600 mt-3">
                <p>หากยังไม่ได้ลงทะเบียน <a href="register.php" class="text-blue-500 hover:underline">สร้างบัญชี</a></p>
            </div>
        </form>
    </div>

</body>
</html>
