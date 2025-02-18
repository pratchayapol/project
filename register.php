<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.src = 'https://www.svgrepo.com/show/521139/eye-show.svg'; // Icon for show
            } else {
                passwordField.type = 'password';
                eyeIcon.src = 'https://www.svgrepo.com/show/524041/eye-closed.svg'; // Icon for hide
            }
        }
    </script>
</head>
<body class="flex items-center justify-center h-screen">

    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded-lg  w-full px-3 py-1">
            <div class="flex items-center justify-center mb-6">
                <img src="https://upload.wikimedia.org/wikipedia/th/e/e0/RMUTI_KORAT.png" alt="RMUTI Logo" class="w-24 h-auto">
            </div>

            <h2 class="text-2xl font-semibold text-center mb-6" style="font-size: 16px;">ลงทะเบียนเข้าสู่เว็บไซต์สำหรับเจ้าหน้าที่ใหม่</h2>

            <form action="register_db.php" method="post">
            <?php if(isset($_SESSION['error'])) : ?>
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                    <h3>
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </h3>
                </div>
            <?php endif ?>

                <!-- First Name -->
                <div class="mb-4">
                    <label for="firstname" class="block text-sm font-medium text-gray-700">ชื่อ:</label>
                    <input type="text" name="firstname" id="firstname" 
                           class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                           placeholder="ชื่อ" required>
                </div>

                <!-- Last Name -->
                <div class="mb-4">
                    <label for="lastname" class="block text-sm font-medium text-gray-700">นามสกุล:</label>
                    <input type="text" name="lastname" id="lastname" 
                           class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                           placeholder="นามสกุล" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">อีเมล:</label>
                    <input type="email" name="email" id="email" 
                           class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                           placeholder="อีเมล" required>
                </div>

                <!-- Password -->
                <div class="mb-4 relative">
                    <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน:</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               pattern=".{6,}" 
                               class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 pr-10" 
                               placeholder="รหัสผ่าน" required>
                        <span onclick="togglePasswordVisibility('password')" 
                              class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                            <img id="eye-icon-password" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                                 alt="Toggle visibility" class="w-5 h-5">
                        </span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6 relative">
                    <label for="password_2" class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน:</label>
                    <div class="relative">
                        <input type="password" name="password_2" id="password_2" 
                               pattern=".{6,}" 
                               class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 pr-10" 
                               placeholder="ยืนยันรหัสผ่าน" required>
                        <span onclick="togglePasswordVisibility('password_2')" 
                              class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                            <img id="eye-icon-password_2" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                                 alt="Toggle visibility" class="w-5 h-5">
                        </span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="mb-4">
                    <p class="text-sm text-gray-600">หากลงทะเบียนแล้ว <a href="login.php" class="text-indigo-600 hover:underline">เข้าสู่ระบบ</a></p>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" name="reg_user" 
                    class="w-full bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300 font-medium py-2 px-4 rounded-lg shadow-sm mt-3">
                        ยืนยัน
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
