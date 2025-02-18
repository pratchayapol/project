<?php
session_start();
include('server.php'); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าเป็น superadmin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'superadmin') {
    $_SESSION['error'] = "Access denied!";
    header("Location: login.php");
    exit();
}

// รับค่าการแก้ไขบทบาท
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['new_role'];

    // หากเลือก superadmin ตรวจสอบว่ามีอยู่แล้วหรือไม่
    if ($new_role === 'superadmin') {
        $check_superadmin_query = "SELECT id FROM user_register WHERE role = 'superadmin'";
        $result = $conn->query($check_superadmin_query);

        if ($result && $result->num_rows > 0) {
            // ลบ superadmin คนเดิม
            $remove_superadmin_query = "UPDATE user_register SET role = 'admin' WHERE role = 'superadmin'";
            $conn->query($remove_superadmin_query);
        }
    }

    // อัปเดตบทบาทผู้ใช้
    $update_role_query = "UPDATE user_register SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($update_role_query);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Role updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update role: " . $stmt->error;
    }

    header("Location: manage.php");
    exit();
}

// ดึงข้อมูลผู้ใช้ทั้งหมด
$query = "SELECT id, firstname, lastname, email, role FROM user_register";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
        .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
        }

        /* Sliding list styles */
        #notification-list {
            display: none;
            position: absolute;
            top: 40px;
            right: 0;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            max-height: 300px;
            overflow-y: auto;
            border-radius: 8px;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        #notification-list.show {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f7f7f7;
        }
    </style>
    <script>
        function openModal(userId, userRole) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('overlay').classList.remove('hidden');
            document.getElementById('user_id').value = userId;
            document.getElementById('current_role').innerText = userRole;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('overlay').classList.add('hidden');
        }
        document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById('menu-toggle');
        const menuClose = document.getElementById('menu-close');
        const menu = document.getElementById('menu');
        const overlay = document.getElementById('overlay');

        // เปิดเมนู
        menuToggle.addEventListener('click', () => {
            menu.classList.add('open');
            overlay.classList.remove('hidden');
        });

        // ปิดเมนูเมื่อกดปุ่มปิด
        menuClose.addEventListener('click', () => {
            menu.classList.remove('open');
            overlay.classList.add('hidden');
        });

        // ปิดเมนูเมื่อกดที่ overlay
        overlay.addEventListener('click', () => {
            menu.classList.remove('open');
            overlay.classList.add('hidden');
        });
    });
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
        function openModal(userId, userRole) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('overlay').classList.remove('hidden');
            document.getElementById('user_id').value = userId;
            document.getElementById('current_role').innerText = userRole;
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.getElementById('overlay').classList.add('hidden');
        }

        function searchTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");
            
            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                let showRow = false;

                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        showRow = true;
                    }
                });

                row.style.display = showRow ? "" : "none";
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const menuToggle = document.getElementById('menu-toggle');
            const menuClose = document.getElementById('menu-close');
            const leftMenu = document.getElementById('left-menu');
            const overlay = document.getElementById('overlay');

            // Toggle menu visibility
            menuToggle.addEventListener('click', () => leftMenu.classList.add('open'));
            menuClose.addEventListener('click', () => leftMenu.classList.remove('open'));
            overlay.addEventListener('click', () => leftMenu.classList.remove('open'));
        });
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
            <h1 class="text-xl font-semibold">จัดการกับสถานะผู้ใช้</h1>
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
                <li><a href="chack_device.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจเช็คอุปกรณ์</a></li>
                <li><a href="user_sum1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
                <li><a href="dashboard1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
            </ul>
        </nav>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <input 
                type="text" 
                id="searchInput" 
                class="p-2 border border-gray-300 rounded w-full" 
                placeholder="ค้นหาข้อมูล"
            />
            <button 
                onclick="searchTable()" 
                class="mt-2 p-2 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white rounded w-full"
            >
                ค้นหา
            </button>
         </div>

         <div class="overflow-x-auto">
        <table id="carInfoTable" class="table-auto w-full border-collapse border border-gray-300 text-xs sm:text-sm md:text-base">
            <thead class="bg-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <th class="border px-2 py-1 whitespace-nowrap">ID</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ชื่อ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">นามสกุล</th>
                        <th class="border px-2 py-1 whitespace-nowrap">อีเมล</th>
                        <th class="border px-2 py-1 whitespace-nowrap">สถานะ</th>
                        <th class="border px-2 py-1 whitespace-nowrap"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-100">
                            <td class="border px-2 py-1 whitespace-nowrap"><?php echo $row['id']; ?></td>
                            <td class="border px-2 py-1 whitespace-nowrap"><?php echo $row['firstname']; ?></td>
                            <td class="border px-2 py-1 whitespace-nowrap"><?php echo $row['lastname']; ?></td>
                            <td class="border px-2 py-1 whitespace-nowrap"><?php echo $row['email']; ?></td>
                            <td class="border px-2 py-1 whitespace-nowrap"><?php echo $row['role']; ?></td>
                            <td class="py-3 px-4 text-center">
                                <button class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500" 
                                        onclick="openModal(<?php echo $row['id']; ?>, '<?php echo $row['role']; ?>')">
                                    แก้ไข
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden" onclick="closeModal()"></div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <form method="POST">
                <h2 class="text-xl font-bold mb-4">สถานะเจ้าหน้าที่:</h2>
                <p class="mb-4">สถานะเดิม: <span id="current_role" class="font-semibold"></span></p>
                <input type="hidden" name="user_id" id="user_id">

                <label for="new_role" class="block text-sm font-medium text-gray-700 mb-2">:</label>
                <select name="new_role" id="new_role" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="admin">Admin</option>
                    <option value="superadmin">Superadmin</option>
                </select>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" class="bg-red-800 text-white px-4 py-2 rounded hover:bg-red-400" onclick="closeModal()">ยกเลิก</button>
                    <button type="submit" name="update_role" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">เปลี่ยนแปลง</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
