<?php
session_start();
include('server.php');

// ตรวจสอบว่ามีข้อมูลใน session หรือไม่
if (!isset($_SESSION['email'])) {
    echo "ไม่มีข้อมูลที่จะแก้ไข";
    exit();
}

// ดึงข้อมูลจากฐานข้อมูลเมื่อเปิดหน้า
$sql = "SELECT * FROM paymentinfo WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // เก็บข้อมูลในเซสชั่น
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['phone_number'] = $user['phone_number'];
} else {
    echo "ไม่พบข้อมูล";
    exit();
}

// ตรวจสอบว่ามีการส่งฟอร์มมาแล้ว
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // ตรวจสอบความถูกต้องของข้อมูล
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        echo "รูปแบบอีเมลไม่ถูกต้อง";
        exit();
    }
    if (!preg_match('/^[0-9]{10}$/', $_POST['phone_number'])) {
        echo "หมายเลขโทรศัพท์ควรมีความยาว 10 หลัก";
        exit();
    }

    // รับข้อมูลและป้องกัน SQL Injection
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE paymentinfo SET first_name = ?, last_name = ?, email = ?, phone_number = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone_number, $_SESSION['email']);

    if ($stmt->execute()) {
        // อัปเดตข้อมูลในเซสชั่น
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone_number'] = $phone_number;

        // นำไปสู่หน้าแสดงข้อมูล
        header("Location: show_user_db.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูล</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <style>
        body {
            background: url('https://cdn.pixabay.com/photo/2015/07/24/11/11/watercolor-858169_640.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
    <script>
        function confirmCancel() {
            Swal.fire({
                title: 'คุณแน่ใจหรือไม่?',
                text: "คุณต้องการออกจากหน้านี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:outline-none',
                    cancelButton: 'bg-red-500 text-white hover:bg-red-600 focus:outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "show_user_db.php";
                }
            });
        }

        function confirmSave() {
            Swal.fire({
                title: 'คุณต้องการบันทึกข้อมูลหรือไม่?',
                text: "ข้อมูลของคุณจะถูกบันทึก",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ตกลง',
                cancelButtonText: 'ยกเลิก',
                customClass: {
                    confirmButton: 'bg-green-500 text-white hover:bg-green-600 focus:outline-none',
                    cancelButton: 'bg-red-500 text-white hover:bg-red-600 focus:outline-none'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.forms[0].submit();
                }
            });
        }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased flex items-center justify-center h-screen">

<div class="w-full sm:w-80 md:w-[600px] lg:w-[500px] mx-auto mt-4 p-4 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">แก้ไขข้อมูล</h2>
    
    <form method="post" action="" class="space-y-4">
        <div>
            <label for="first_name" class="block text-gray-700">ชื่อ</label>
            <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($_SESSION['first_name']); ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="last_name" class="block text-gray-700">นามสกุล</label>
            <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($_SESSION['last_name']); ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div>
            <label for="email" class="block text-gray-700">อีเมล</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="phone_number" class="block text-gray-700">เบอร์โทร</label>
            <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($_SESSION['phone_number']); ?>"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex justify-between items-center">
            <button type="button" onclick="confirmCancel()" 
                class="w-1/2 py-2 px-4 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none">
                ยกเลิก
            </button>
            <button type="button" onclick="confirmSave()" 
                class="w-1/2 py-2 px-4 bg-green-400 text-white rounded-md hover:bg-green-500 focus:outline-none">
                บันทึกการแก้ไข
            </button>
        </div>
        <input type="hidden" name="update" value="1">
    </form>
</div>
</body>
</html>
