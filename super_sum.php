

<?php
include("server.php");

$date_filter = "";
$name_filter = "";
$email_filter = "";

// กรองวันที่
if (isset($_POST['searchDate']) && !empty($_POST['searchDate'])) {
    $search_date = $conn->real_escape_string($_POST['searchDate']);
    $date_filter = "WHERE DATE(created_at) = '$search_date'";
}

// กรองชื่อ
if (isset($_POST['searchName']) && !empty($_POST['searchName'])) {
    $search_name = $conn->real_escape_string($_POST['searchName']);
    if ($date_filter == "") {
        $name_filter = "WHERE firstname LIKE '%$search_name%' OR lastname LIKE '%$search_name%'";
    } else {
        $name_filter = "AND (firstname LIKE '%$search_name%' OR lastname LIKE '%$search_name%')";
    }
}

// กรองอีเมล
if (isset($_POST['searchEmail']) && !empty($_POST['searchEmail'])) {
    $search_email = $conn->real_escape_string($_POST['searchEmail']);
    if ($date_filter == "" && $name_filter == "") {
        $email_filter = "WHERE email LIKE '%$search_email%'";
    } else {
        $email_filter = "AND email LIKE '%$search_email%'";
    }
}

// รวมการกรอง
$sql = "SELECT firstname, lastname, email, phone, password_lock, created_at FROM user_register $date_filter $name_filter $email_filter";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ข้อมูลเจ้าหน้าที่ภายในระบบ</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <style>
        body { font-family: 'Kanit', sans-serif; } 
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
    </script>
</head>
<body class="bg-gray-100"style="font-family: 'Kanit', sans-serif;">
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-xl" style="font-family: 'Kanit', sans-serif;">เจ้าหน้าที่ในระบบทั้งหมด</h1>
            <div class="flex items-center space-x-2">
                <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">Logout</a>
                <div class="relative group cursor-pointer">
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="profile1.php" class="block px-4 py-2 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 bg-white shadow-lg">
        <div class="p-4 bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white flex justify-between items-center">
            <h2 class=" text-lg"style="font-family: 'Kanit', sans-serif;"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4"style="font-family: 'Kanit', sans-serif;">
            <ul>
            <li><a href="super_admin.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="super_check.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจสอบสถานะคำร้องอนุมัติ</a></li>
                <li><a href="manage.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">จัดการผู้ใช้งาน</a></li>
                <li><a href="manage.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจเช็คอุปกรณ์</a></li>
                <li><a href="user_sum1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
                <li><a href="dashboard1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto px-4 py-6">
    <form method="POST" class="grid grid-cols-2 gap-4 mb-4 bg-white p-4 shadow-lg rounded-lg">
        <input type="text" name="searchName" placeholder="ชื่อ - นามสกุล" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchName']) ? $_POST['searchName'] : ''; ?>">
        <input type="date" name="searchDate" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchDate']) ? $_POST['searchDate'] : ''; ?>">
        <input type="email" name="searchEmail" placeholder="อีเมล" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchEmail']) ? $_POST['searchEmail'] : ''; ?>">
        <button type="submit" class="col-span-2 p-2 bg-blue-500 text-white rounded">ค้นหา</button>
    </form>
    <div class="overflow-x-auto">
        <table id="example" class="display nowrap table-auto w-full border-collapse border border-gray-300 text-xs sm:text-sm md:text-base">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="border px-2 py-1">ชื่อ</th>
                    <th class="border px-2 py-1">นามสกุล</th>
                    <th class="border px-2 py-1">อีเมล</th>
                    <th class="border px-2 py-1">เบอร์โทรติดต่อ</th>
                    <th class="border px-2 py-1">รหัสเข้าระบบล็อกอุปกรณ์</th>
                    <th class="border px-2 py-1">วันที่ลงทะเบียน</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["firstname"]) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["lastname"]) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["email"]) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["phone"]) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["password_lock"]) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars($row["created_at"]) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='border px-2 py-1 text-center'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
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
        $(document).ready(function () {
        $('#example').DataTable({
            dom: "<'flex justify-between space-x-4'<'w-1/2'l><'w-1/2'f>>" +  // ใช้ Flexbox พร้อมเว้นวรรค
                 "<'overflow-x-auto'<'w-full' tr>>" + 
                 "<'flex justify-between space-x-4'<'w-1/2'i><'w-1/2'p>>" + // ใช้ Flexbox พร้อมเว้นวรรคสำหรับการแบ่งหน้า
                 "<'mt-4'<'w-full'B>>", // เว้นวรรคให้ปุ่ม
            buttons: [
                {
                    extend: 'copy',
                    className: 'bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300'
                },
                {
                    extend: 'csv',
                    className: 'bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300'
                },
                {
                    extend: 'excel',
                    className: 'bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300'
                },
                {
                    extend: 'pdf',
                    className: 'bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300'
                },
                {
                    extend: 'print',
                    className: 'bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition duration-300'
                }
            ],
            searching: true,
            paging: true,
            lengthMenu: [5, 10, 25, 50, 100],
            pageLength: 10,
            language: {
                paginate: {
                    previous: "«",
                    next: "»"
                },
                lengthMenu: "แสดง _MENU_ รายการ",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                searching: false
            }
        });
    });
    </script>
</body>
</html>
