<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: url('https://cdn.pixabay.com/photo/2015/07/24/11/11/watercolor-858169_640.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded-lg p-4 w-full max-w-md">
            <div class="flex items-center justify-center mb-6">
                <img src="https://upload.wikimedia.org/wikipedia/th/e/e0/RMUTI_KORAT.png" alt="RMUTI Logo" class="w-24 h-auto">
            </div>

            <h2 class="text-2xl font-semibold text-center mb-4" style="font-size: 16px;">ลงทะเบียนเข้าสู่เว็บไซต์สำหรัชำระค่าปรับ</h2>

            <form action="loginuser_db.php" method="post">
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

            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">ชื่อจริง</label>
                <input type="text" id="first_name" name="first_name" required
                class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                placeholder="ชื่จริง" required>
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                <input type="text" id="last_name" name="last_name" required
                class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                placeholder="นามสุกล" required>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                <input type="email" id="email" name="email" required
                class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                placeholder="อีเมล" required>
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700">เบอร์โทร</label>
                <input type="text" id="phone_number" name="phone_number" required
                class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                placeholder="เบอร์โทร" required>
            </div>

            <div>
                <label for="plate_number" class="block text-sm font-medium text-gray-700">หมายเลขทะเบียนรถ</label>
                <input type="text" id="plate_number" name="plate_number" required
                class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-200" 
                placeholder="หมายเลขทะเบียนรถ" required>
            </div>

            <div class="mb-4">
                <label for="province" class="block text-sm font-medium text-gray-700">จังหวัด:</label>
                <select id="province" name="province" required class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">--เลือกจังหวัด--</option>
                    <?php
                    include 'server.php';
                    $sql = "SELECT province_name_th FROM provinces";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['province_name_th'].'">'.$row['province_name_th'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="device_user" class="block text-sm font-medium text-gray-700">หมายเลขอุปกรณ์ล็อค:</label>
                <select id="id" name="device_user" required class="mt-1 block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">--เลือกหมายเลขอุปกรณ์ล็อค--</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center">
                <button type="button" onclick="goHome()"
                    class="px-5 py-2 bg-gradient-to-r from-[#800000] to-[#B21807] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    ยกเลิก
                </button>
                <button type="submit" name="submit_payment"
                    class="px-5 py-2 bg-gradient-to-r from-[#50C878] to-[#1B8A6B] text-white text-lg font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:shadow-2xl transition duration-300">
                    ขั้นตอนต่อไป
                </button>
            </div>
        </form>
    </div>

    <script>
        function goHome() {
            Swal.fire({
                title: 'ยืนยันการยกเลิก?',
                text: "คุณแน่ใจว่าต้องการออกจากหน้านี้หรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ไม่ใช่',
                confirmButtonColor: '#38a169',  // สีเขียวสำหรับปุ่มอนุมัติ
                cancelButtonColor: '#e53e3e',  // สีแดงสำหรับปุ่มไม่อนุมัติ
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "home1.php"; // Redirect to home1.php
                }
            });
        }
    </script>
</body>
</html>
