<?php
session_start();
include('server.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch user data
$query = "SELECT * FROM user_register WHERE email = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Error preparing the SQL statement: ' . $conn->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input sanitization
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $profileImage = $user['profile_image'];
    $errors = [];

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $tmpName = $_FILES['profile_image']['tmp_name'];
        $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
        $targetFile = $uploadDir . $fileName;
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array(mime_content_type($tmpName), $allowedTypes)) {
            if (move_uploaded_file($tmpName, $targetFile)) {
                $profileImage = $fileName;
            } else {
                $errors[] = "Failed to upload image.";
            }
        } else {
            $errors[] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
        }
    }

    // Password validation and update
    if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if (!password_verify($currentPassword, $user['password'])) {
            $errors[] = "Incorrect current password.";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "New password and confirmation do not match.";
        } elseif (strlen($newPassword) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    // Update user data in database
    if (empty($errors)) {
        $query = "UPDATE user_register SET firstname = ?, lastname = ?, phone = ?, profile_image = ?";
        if (isset($hashedPassword)) {
            $query .= ", password = ?";
        }
        $query .= " WHERE email = ?";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die('Error preparing the SQL statement: ' . $conn->error);
        }

        if (isset($hashedPassword)) {
            $stmt->bind_param("ssssss", $firstname, $lastname, $phone, $profileImage, $hashedPassword, $email);
        } else {
            $stmt->bind_param("sssss", $firstname, $lastname, $phone, $profileImage, $email);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully.";
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = "Failed to update profile. Please try again later.";
        }
    }
}
?>


<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(eye-icon-${fieldId});

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
<div class="container mx-auto px-4 py-8">
    <!-- Alert Messages -->
    <?php if (!empty($errors)): ?>
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="edit_profile.php" method="POST" enctype="multipart/form-data" class="max-w-md mx-auto bg-white p-2 rounded-lg shadow-md">
        
        <!-- Profile Image Upload -->
        <div class="mb-4">
            <label for="profile_image" class="block text-gray-700 font-semibold mb-2">อัพโหลดรูปภาพ </label>
            <div class="flex items-center">
                <img src="uploads/<?php echo htmlspecialchars($user['profile_image'] ?? 'default.png'); ?>" alt="Profile Image" class="w-24 h-24 object-cover rounded-full mr-4">
                <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-500 file:bg-gray-100 file:border-0 file:mr-4 file:rounded-lg file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200">
            </div>
        </div>

        <div class="mb-4">
            <label for="firstname" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-user text-gray-500 mr-2"></i> ชื่อจริง
            </label>
            <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <div class="mb-4">
            <label for="lastname" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-user text-gray-500 mr-2"></i> นามสกุล
            </label>
            <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-phone text-gray-500 mr-2"></i> เบอร์โทรศัพท์
            </label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
        </div>
        <div class="mb-4 relative">
            <label for="current_password" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-lock text-gray-500 mr-2"></i> รหัสผ่านเดิม
            </label>
            <div class="relative">
                <input type="password" name="current_password" id="current_password" 
                    pattern=".{6,}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 pr-10" 
                    placeholder="รหัสผ่านเดิม" required>
                <span onclick="togglePasswordVisibility('current_password')" 
                    class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                    <img id="eye-icon-current_password" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                    alt="Toggle visibility" class="w-5 h-5">
                </span>
            </div>
        </div>

        <!-- New Password -->
        <div class="mb-4 relative">
            <label for="new_password" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-lock text-gray-500 mr-2"></i> ตั้งรหัสผ่านใหม่
            </label>
            <div class="relative">
                <input type="password" name="new_password" id="new_password" 
                    pattern=".{6,}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 pr-10" 
                    placeholder="ตั้งรหัสผ่านใหม่" required>
                <span onclick="togglePasswordVisibility('new_password')" 
                    class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                    <img id="eye-icon-new_password" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                    alt="Toggle visibility" class="w-5 h-5">
                </span>
            </div>
        </div>

        <!-- Confirm New Password -->
        <div class="mb-4 relative">
            <label for="confirm_password" class="block text-gray-700 font-semibold mb-2">
                <i class="fas fa-lock text-gray-500 mr-2"></i> ยืนยันรหัสผ่านใหม่
            </label>
            <div class="relative">
                <input type="password" name="confirm_password" id="confirm_password" 
                    pattern=".{6,}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200 pr-10" 
                    placeholder="ยืนยันรหัสผ่านใหม่" required>
                <span onclick="togglePasswordVisibility('confirm_password')" 
                    class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                    <img id="eye-icon-confirm_password" src="https://www.svgrepo.com/show/524041/eye-closed.svg" 
                    alt="Toggle visibility" class="w-5 h-5">
                </span>
            </div>
        </div>
        <!-- Save Changes Button -->
        <div class="mt-8 flex justify-center space-x-6">
        <button type="button" 
                onclick="showAlert('แก้ไขข้อมูลส่วนตัว', 'คุณต้องการบันทึกการเปลี่ยนแปลงหรือไม่?')" 
                class="bg-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-green-500 transform hover:scale-105 transition">
            บันทึกข้อมูล
        </button>
        <a href="profile.php" 
            onclick="showAlert('ย้อนกลับ', 'คุณต้องการย้อนกลับไปยังเมนูหลักหรือไม่?', this.href)" 
            class="bg-red-700 text-white px-6 py-3 rounded-xl shadow-lg hover:bg-red-400 transform hover:scale-105 transition">
            ย้อนกลับ
        </a>
    </div>
    </form>
</div>
<script>
    function showAlert(title, text) {
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
            document.querySelector('form').submit(); // ส่งข้อมูลในฟอร์ม
        }
    });
}
</script>
</body>
</html>