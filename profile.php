<?php
session_start();
include('server.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user data
$query = "SELECT * FROM user_register WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // Handle case where user does not exist
    die("ไม่มีข้อมูลผู้ใช้ในระบบ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Profile Picture -->
            <div class="w-full flex justify-center pt-8">
                <div class="w-40 h-40 bg-gradient-to-r from-[#2B547E] to-[#29465B] rounded-full flex items-center justify-center">
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_image'] ?? 'default.png'); ?>" 
                         alt="Profile Image" 
                         class="w-36 h-36 object-cover rounded-full border-4 border-white shadow-md">
                </div>
            </div>
            <!-- Card Content -->
            <div class="pt-8 pb-8 px-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-800"style="font-family: 'Kanit', sans-serif;">ข้อมูลส่วนตัว</h2>
                <div class="mt-8 space-y-6">
                    <div class="text-left bg-gray-100 p-2 rounded-lg shadow">
                        <p class="text-lg text-gray-700"><i class="fas fa-user text-gray-500 mr-2"></i><span class="text-gray-900"style="font-family: 'Kanit', sans-serif;">ชื่อจริง:</span> <?php echo htmlspecialchars($user['firstname']); ?></p>
                    </div>
                    <div class="text-left bg-gray-100 p-2 rounded-lg shadow">
                        <p class="text-lg text-gray-700"><i class="fas fa-user text-gray-500 mr-2"></i><span class="text-gray-900"style="font-family: 'Kanit', sans-serif;">นามสกุล:</span> <?php echo htmlspecialchars($user['lastname']); ?></p>
                    </div>
                    <div class="text-left bg-gray-100 p-2 rounded-lg shadow">
                        <p class="text-lg text-gray-700"><i class="fas fa-envelope text-gray-500 mr-2"></i><span class="text-gray-900"style="font-family: 'Kanit', sans-serif;">อีเมล:</span> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="text-left bg-gray-100 p-2 rounded-lg shadow">
                        <p class="text-lg text-gray-700"><i class="fas fa-phone text-gray-500 mr-2"></i><span class="text-gray-900"style="font-family: 'Kanit', sans-serif;">เบอร์โทรที่ติดต่อได้:</span> <?php echo htmlspecialchars($user['phone']); ?></p>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex flex-col items-center space-y-4 sm:flex-row sm:justify-center sm:space-x-6 sm:space-y-0">
                    <a href="edit_profile.php" 
                    onclick="showAlert('แก้ไขข้อมูลส่วนตัว', 'คุณต้องการแก้ไขข้อมูลส่วนตัวหรือไม่?', this.href)" 
                    class="w-full max-w-[300px] bg-[#FFA500] text-white px-6 py-3 rounded-xl shadow-lg hover:bg-[#E9AB17] transform hover:scale-105 transition text-center">
                    แก้ไขข้อมูลส่วนตัว
                    </a>
                    <a href="menu_home.php" 
                    onclick="showAlert('ย้อนกลับ', 'คุณต้องการย้อนกลับไปยังเมนูหลักหรือไม่?', this.href)" 
                    class="w-full max-w-[300px] bg-[#990000] text-white px-6 py-3 rounded-xl shadow-lg hover:bg-[#C11B17] transform hover:scale-105 transition text-center">
                    ย้อนกลับ
                    </a>
                </div>
                <!-- ฟังก์ชัน JavaScript -->
                <script>
                    function showAlert(title, text, url) {
                        event.preventDefault(); // ป้องกันการเปลี่ยนหน้า
                        Swal.fire({
                            title: title,
                            text: text,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'ตกลง',
                            cancelButtonText: 'ยกเลิก'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url; // ยืนยันการเปลี่ยนหน้า
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</body>
</html>
