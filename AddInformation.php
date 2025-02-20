<?php
    session_start();
    include('server.php');

    // ตรวจสอบว่า user_id อยู่ใน session หรือไม่
    if (!isset($_SESSION['user_id'])) {
        die("กรุณาล็อกอินก่อนดำเนินการ");
    }

    // ดึงข้อมูลชื่อจากฐานข้อมูล
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT firstname FROM user_register WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($firstname);
        $stmt->fetch();
        $stmt->close();
    } else {
        die("เกิดข้อผิดพลาดในการดึงข้อมูลชื่อผู้ใช้");
    }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกข้อมูลรถ</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .bg-custom-green {
            background-color: #12AD2B;
        }
        .hover-bg-custom-green:hover {
            background-color: #0F8D23;
        }
        body {
            font-family: 'Kanit', sans-serif;
            background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
<div class="bg-white rounded-lg p-4 w-4/5 sm:w-2/3 md:w-1/2 mx-auto shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center" style="font-family: 'Kanit', sans-serif;">กรอกข้อมูลรถ</h1>
        <form action="AddInformation_db.php" method="post" id="carInfoForm">
            <!-- เลขทะเบียนรถ -->
            <div class="mb-4">
                <label for="plate_number" class="block text-sm text-gray-700">เลขทะเบียนรถ:</label>
                <input type="text" id="plate_number" name="plate_number" required pattern="[\dก-๙]+" title="กรุณากรอกเฉพาะตัวเลขหรืออักษรไทยเท่านั้น" class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- จังหวัด -->
            <div class="mb-4">
                <label for="province" class="block text-sm text-gray-700"style="font-family: 'Kanit', sans-serif;">จังหวัด:</label>
                <select id="province" name="province" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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

            <!-- ประเภทรถ -->
            <div class="mb-4">
                <label for="car_type" class="block text-sm text-gray-700" style="font-family: 'Kanit', sans-serif;">ประเภทรถ:</label>
                <select id="car_type" name="car_type" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">--เลือกประเภทรถ--</option>
                    <?php
                    $sql = "SELECT type_name_th FROM car_types";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['type_name_th'].'">'.$row['type_name_th'].'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- ยี่ห้อรถ -->
            <div class="mb-4">
                <label for="brand" class="block text-sm text-gray-700" style="font-family: 'Kanit', sans-serif;">ยี่ห้อรถ:</label>
                <select id="brand" name="brand" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">--เลือกยี่ห้อรถ--</option>
                    <?php
                    $sql = "SELECT brand_name_en, brand_name_th, vehicle_category FROM car_brands";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['brand_name_en'].'">'.$row['brand_name_th'].', '.$row['brand_name_en'].' ('.$row['vehicle_category'].')</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- ยอดชำระค่าปรับ -->
            <div class="mb-4">
                <label for="price" class="block text-sm text-gray-700" style="font-family: 'Kanit', sans-serif;">ยอดชำระค่าปรับ:</label>
                <input type="number" id="price" name="price" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- อุปกรณ์ -->
            <div class="mb-4">
                <label for="device" class="block text-sm text-gray-700" style="font-family: 'Kanit', sans-serif;">อุปกรณ์:</label>
                <input type="text" name="device" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- รหัสปลดล็อก -->
            <div class="mb-4">
                <label for="unlockcar" class="block text-sm text-gray-700" style="font-family: 'Kanit', sans-serif;">รหัสปลดล็อก:</label>
                <input type="text" id="unlockcar" name="unlockcar" readonly class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                
                <!-- ใช้ flexbox เพื่อจัดปุ่มให้อยู่ข้างกัน -->
                <div class="flex space-x-2 mt-2">
                    <button type="button" onclick="generateUnlockCode()" class="w-full sm:w-auto px-10 py-1 bg-indigo-500 text-white rounded-md shadow-sm">
                        สุ่มรหัส
                    </button>
                    <button type="button" onclick="copyAndOpen()" class="w-full sm:w-auto px-10 py-1 bg-indigo-500 text-white rounded-md shadow-sm">
                        คัดลอก
                    </button>
                </div>
            </div>
            <p class="text-lg mb-4" style="font-family: 'Kanit', sans-serif;">ชื่อเจ้าหน้าที่บันทึก <span class="text-indigo-600"><?= htmlspecialchars($firstname) ?></span></p>

            <!-- ปุ่ม -->
            <div class="flex justify-between">
                <button 
                    type="button" 
                    onclick="confirmBack()" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    ย้อนกลับ
                </button>
                <button 
                    type="button" 
                    onclick="confirmSave()" 
                    class="px-4 py-2 bg-custom-green text-white rounded-md hover-bg-custom-green focus:outline-none focus:ring-2 focus:ring-[#12AD2B] focus:ring-offset-2">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
        
        <!-- JavaScript -->
        <script>
            function confirmBack() {
                Swal.fire({
                    title: 'คุณต้องการย้อนกลับไปหน้าก่อนหน้านี้หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่',
                    cancelButtonText: 'ไม่',
                    customClass: {
                        confirmButton: 'swal-green-btn',
                        cancelButton: 'swal-red-btn'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        history.back();
                    }
                });
            }

            function confirmSave() {
                Swal.fire({
                    title: 'คุณต้องการบันทึกข้อมูลหรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่',
                    cancelButtonText: 'ไม่',
                    customClass: {
                        confirmButton: 'swal-green-btn',
                        cancelButton: 'swal-red-btn'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('carInfoForm').submit(); // ส่งฟอร์ม
                    }
                });
            }

            function generateUnlockCode() {
                let unlockCode = Math.floor(100000 + Math.random() * 900000); // สุ่มเลข 6 หลัก
                document.getElementById("unlockcar").value = unlockCode;
            }

            // เมื่อโหลดหน้าเว็บ ให้แจ้งเตือนว่า "หน้านี้ถูกโหลดใหม่"
            window.onload = function() {
                generateUnlockCode(); // สุ่มรหัสใหม่

                Swal.fire({
                    title: "หน้าเว็บถูกโหลดใหม่",
                    text: "รหัสปลดล็อกถูกสุ่มใหม่แล้ว!",
                    icon: "info",
                    confirmButtonText: "ตกลง"
                });
            };

            function copyAndOpen() {
                // คัดลอกตัวเลขจากช่อง input
                const code = document.getElementById('unlockcar');
                code.select();
                document.execCommand('copy');

                // แสดงป๊อปอัพ SweetAlert เมื่อคัดลอกสำเร็จ
                Swal.fire({
                    icon: 'success',
                    title: 'คัดลอกสำเร็จ',
                    text: 'รหัสปลดล็อกถูกคัดลอกไปยังคลิปบอร์ดแล้ว!',
                    confirmButtonText: 'ตกลง'
                });
            }
        </script>
        <style>
            .swal-green-btn {
                background-color: #28a745 !important; /* สีเขียว */
                color: #fff !important;
            }

            .swal-green-btn:hover {
                background-color: #218838 !important; /* สีเขียวเข้ม */
            }

            .swal-red-btn {
                background-color: #dc3545 !important; /* สีแดง */
                color: #fff !important;
            }

            .swal-red-btn:hover {
                background-color: #c82333 !important; /* สีแดงเข้ม */
            }
        </style>
    </div>
</body>
</html>