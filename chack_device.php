<?php
session_start();
include('server.php');
// คำสั่ง SQL เพื่อดึงข้อมูล
$sql = "SELECT 
            information_car.plate_number, 
            information_car.province, 
            information_car.car_type, 
            information_car.brand, 
            information_car.price, 
            information_car.device, 
            information_car.unlockcar, 
            information_car.created_at, 
            user_register.firstname, 
            user_register.lastname, 
            user_register.email 
        FROM information_car
        JOIN user_register 
        ON information_car.staff_id = user_register.id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
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
</script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-xl font-semibold">ตรวจเช็คอุปกรณ์ล็อกล้อ</h1>
            <div class="flex items-center space-x-6">
                <div class="relative group cursor-pointer">
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="Profile1.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 bg-white shadow-lg">
        <div class="p-4 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <h2 class=" text-lg font-semibold"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4">
            <ul>
                <li><a href="super_admin.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="super_check.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบสถานะคำร้องอนุมัติ</a></li>
                <li><a href="super_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ใช้ทั้งหมด</a></li>
                <li><a href="manage.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">จัดการผู้ใช้งาน</a></li>
                <li><a href="user_sum1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
                <li><a href="dashboard1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดยอดชำระค่าปรับรายเดือน</a></li>
            </ul>
        </nav>
    </div>

    <div class="container mx-auto px-4 py-6">
    <div class="mb-4">
        <input 
            type="text" 
            id="searchInput" 
            class="p-2 border border-gray-300 rounded w-full text-sm sm:text-base" 
            placeholder="ค้นหาข้อมูล"
        />
        <button 
            onclick="searchTable()" 
            class="mt-2 p-2 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white rounded w-full text-sm sm:text-base"
        >
            ค้นหา
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="carInfoTable" class="table-auto w-full border-collapse border border-gray-300 text-xs sm:text-sm md:text-base">
            <thead class="bg-gray-200">
                <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border px-2 py-1 whitespace-nowrap">หมายเลขทะเบียน</th>
                    <th class="border px-2 py-1 whitespace-nowrap">จังหวัด</th>
                    <th class="border px-2 py-1 whitespace-nowrap">ประเภท</th>
                    <th class="border px-2 py-1 whitespace-nowrap">ยี่ห้อ</th>
                    <th class="border px-2 py-1 whitespace-nowrap">ยอดชำระยอดชำระค่าปรับ</th>
                    <th class="border px-2 py-1 whitespace-nowrap">อุปกรณ์</th>
                    <th class="border px-2 py-1 whitespace-nowrap">รหัสปลดล็อก</th>
                    <th class="border px-2 py-1 whitespace-nowrap">วันที่บันทึก</th>
                    <th class="border px-2 py-1 whitespace-nowrap">ชื่อ</th>
                    <th class="border px-2 py-1 whitespace-nowrap">อีเมล</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['plate_number'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['province'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['car_type'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['brand'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['price'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['device'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['unlockcar'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['created_at'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
                        echo "<td class='border px-2 py-1 whitespace-nowrap'>" . $row['email'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10' class='border px-2 py-1 whitespace-nowrap text-center'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('left-menu').classList.toggle('open');
        });

        document.getElementById('menu-close').addEventListener('click', function () {
            document.getElementById('left-menu').classList.remove('open');
        });
    </script>
</body>
</html>
