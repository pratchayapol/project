<?php
session_start();
include('server.php');

// ตรวจสอบว่า user_id อยู่ใน session หรือไม่
if (!isset($_SESSION['email'])) {
    die("กรุณาล็อกอินก่อนดำเนินการ");
}

// ดึงข้อมูลรถทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM information_car";
$result = $conn->query($sql);

$user_id = $_SESSION['user_id']
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลรถ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
                body{
                    font-family: 'Kanit', sans-serif;
        }
        #left-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
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

        // ฟังก์ชันค้นหาตาราง
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toLowerCase();
            table = document.getElementById('carInfoTable');
            tr = table.getElementsByTagName('tr');

            // Loop through all table rows (excluding the header)
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td');
                let rowContainsSearchTerm = false;

                // Loop through each column in the row
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            rowContainsSearchTerm = true;
                            break;
                        }
                    }
                }

                // Show the row if it contains the search term, hide it otherwise
                if (rowContainsSearchTerm) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
            
        // ฟังก์ชันตรวจสอบข้อมูล
        function validateForm() {
            let plate_number = document.getElementById("edit_plate_number").value;
            let province = document.getElementById("edit_province").value;
            let car_type = document.getElementById("edit_car_type").value;
            let brand = document.getElementById("edit_brand").value;
            let price = document.getElementById("edit_price").value;
            let device = document.getElementById("edit_device").value;

            if (!plate_number || !province || !car_type || !brand || !price || !device) {
                alert("กรุณากรอกข้อมูลให้ครบถ้วน");
                return false;
            }

            return true;
        }
    </script>
</head>
<body class="bg-gray-100" style="font-family: 'Kanit', sans-serif;">
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-xl" style="font-family: 'Kanit', sans-serif;">แก้ไขข้อมูลรถยนต์</h1>
            <div class="flex items-center space-x-6">
                <div class="relative group cursor-pointer">
                    <!-- User Icon with External SVG -->
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <!-- Dropdown -->
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="Profile.php" class="block px-4 py-2 hover:bg-gray-100"style="font-family: 'Kanit', sans-serif;">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div id="left-menu" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50" style="font-family: 'Kanit', sans-serif;">
        <div class="p-4 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <h2 class=" text-lg" style="font-family: 'Kanit', sans-serif;"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4" style="font-family: 'Kanit', sans-serif;">
            <ul>
                <li><a href="menu_home.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="AddInformation.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">เพิ่มข้อมูล</a></li>
                <li><a href="device.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจเช็คสถานะอุปกรณ์</a></li>
                <li><a href="dashboard.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
                <li><a href="user_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto px-4 py-6" style="font-family: 'Kanit', sans-serif;">
        <div class="mb-6">
            <input 
                type="text" 
                id="searchInput" 
                class="p-2 border border-gray-300 rounded w-full" style="font-family: 'Kanit', sans-serif;"
                placeholder="ค้นหาข้อมูล"
            />
            <button 
                onclick="searchTable()" 
                class="mt-2 p-2 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white rounded w-full" style="font-family: 'Kanit', sans-serif;"
            >
                ค้นหา
            </button>
        </div>

        <!-- ตารางข้อมูลรถ -->
        <div class="overflow-x-auto" style="font-family: 'Kanit', sans-serif;">
            <table id="carInfoTable" class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal" style="font-family: 'Kanit', sans-serif;">
                        <th class="py-3 px-6 text-left whitespace-nowrap">เลขทะเบียนรถ</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">จังหวัด</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">ประเภทรถ</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">ยี่ห้อรถ</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">ยอดชำระค่าปรับ</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">อุปกรณ์</th>
                        <th class="py-3 px-6 text-left whitespace-nowrap">รหัสปลดล็อก</th>
                        <th class="py-3 px-6 text-center whitespace-nowrap">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['plate_number'] ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['province'] ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['car_type'] ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['brand'] ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= number_format($row['price'], 2) ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['device'] ?></td>
                                <td class="py-3 px-6 whitespace-nowrap"><?= $row['unlockcar'] ?></td>
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <button 
                                        onclick="openEditModal(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                        class="bg-orange-400 text-white px-4 py-2 rounded-md hover:bg-orange-500" style="font-family: 'Kanit', sans-serif;">
                                        แก้ไข
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="py-4 text-center text-gray-500" style="font-family: 'Kanit', sans-serif;">ไม่มีข้อมูลรถในระบบ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div id="editModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96">
            <h2 class="text-lg mb-4" style="font-family: 'Kanit', sans-serif;">แก้ไขข้อมูลรถ</h2>
            <form action="update_car.php" method="post" onsubmit="return validateForm()">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="car_id" id="car_id">

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">เลขทะเบียนรถ</label>
                <input type="text" id="edit_plate_number" name="plate_number" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">จังหวัด</label>
                <select id="edit_province" name="province" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
                    <option value="">--เลือกจังหวัด--</option>
                    <?php
                    $sql = "SELECT province_name_th FROM provinces";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="'.$row['province_name_th'].'">'.$row['province_name_th'].'</option>';
                        }
                    }
                    ?>
                </select>

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">ประเภทรถ</label>
                <select id="edit_car_type" name="car_type" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">
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

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">ยี่ห้อรถ</label>
                <select id="edit_brand" name="brand" required class="mt-1 block w-full px-4 py-1 border border-gray-300 rounded-md shadow-sm">
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

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">ยอดชำระค่าปรับ</label>
                <input type="number" id="edit_price" name="price" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">อุปกรณ์</label>
                <input type="text" id="edit_device" name="device" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">

                <label class="block text-gray-700" style="font-family: 'Kanit', sans-serif;">รหัสปลดล็อก</label>
                <input type="text" id="edit_unlockcar" name="unlockcar" value="โชว์ข้อมูลที่กรอกไว้" readonly class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm">

                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600" style="font-family: 'Kanit', sans-serif;">บันทึก</button>
                    <button type="button" onclick="closeEditModal()" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500">ปิด</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // ฟังก์ชันเปิด Modal และใส่ข้อมูล
        function openEditModal(car) {
            document.getElementById('editModal').classList.remove('hidden');

            document.getElementById('edit_plate_number').value = car.plate_number;
            document.getElementById('edit_province').value = car.province;
            document.getElementById('edit_car_type').value = car.car_type;
            document.getElementById('edit_brand').value = car.brand;
            document.getElementById('edit_price').value = car.price;
            document.getElementById('edit_device').value = car.device;
            document.getElementById('edit_unlockcar').value = car.unlockcar;

            // ส่ง car_id ไปยังฟอร์มสำหรับการอัปเดต
            document.getElementById('car_id').value = car.id;
        }

        // ฟังก์ชันปิด Modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
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