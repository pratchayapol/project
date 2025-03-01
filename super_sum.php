<?php
  require_once __DIR__ . '/vendor/autoload.php';
  include("server.php");

  $where = [];
  $params = [];
  $types = "";
  
  // ค้นหาชื่อ-นามสกุล
  if (!empty($_POST['name'])) {
      $where[] = "(firstname LIKE ? OR lastname LIKE ?)";
      $name = "%" . $_POST['name'] . "%";
      $params[] = $name;
      $params[] = $name;
      $types .= "ss";
  }

  // ค้นหาตามวันที่ลงทะเบียน
  if (!empty($_POST['searchDate'])) {
      $where[] = "DATE(created_at) = ?";
      $params[] = $_POST['searchDate'];
      $types .= "s";
  }

  // สร้างเงื่อนไข SQL
  $where_clause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";
  $sql = "SELECT firstname, lastname, email, password_lock, phone, created_at FROM user_register $where_clause ORDER BY created_at DESC";

  $stmt = $conn->prepare($sql);
  if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  $result = $stmt->get_result();

  $conn->close();

  // เริ่มสร้าง PDF
  $mpdf = new \Mpdf\Mpdf();

  // สร้างเนื้อหาของ PDF
  $html = '<h1>ข้อมูลเจ้าหน้าที่ในระบบ</h1>';
  $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
  $html .= '<tr>
              <th>ชื่อ</th>
              <th>นามสกุล</th>
              <th>อีเมล</th>
              <th>เบอร์โทรติดต่อ</th>
              <th>รหัสเข้าระบบล็อกอุปกรณ์</th>
              <th>วันที่ลงทะเบียน</th>
            </tr>';

  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
          $html .= "<tr>
                      <td>" . $row["firstname"] . "</td>
                      <td>" . $row["lastname"] . "</td>
                      <td>" . $row["email"] . "</td>
                      <td>" . $row["phone"] . "</td>
                      <td>" . $row["password_lock"] . "</td>
                      <td>" . $row["created_at"] . "</td>
                    </tr>";
      }
  } else {
      $html .= "<tr><td colspan='6' class='text-center'>ไม่มีข้อมูล</td></tr>";
  }

  $html .= '</table>';

  // สร้าง PDF
  $mpdf->WriteHTML($html);

  // ส่งไฟล์ PDF ให้ผู้ใช้ดาวน์โหลด
  $mpdf->Output('user_data.pdf', 'D'); // 'D' คือให้ดาวน์โหลด
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
</head>
<style>
        body{
          font-family: 'Kanit', sans-serif;
        }  
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
            <h1 class="text-xl"style="font-family: 'Kanit', sans-serif;">เจ้าหน้าที่ในระบบทั้งหมด</h1>
            <div class="flex items-center space-x-6">
                <div class="relative group cursor-pointer">
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="profile1.php" class="block px-4 py-2 hover:bg-gray-100"style="font-family: 'Kanit', sans-serif;">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100"style="font-family: 'Kanit', sans-serif;">Logout</a>
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
    <!-- ฟอร์มค้นหา -->
    <form method="POST" class="grid grid-cols-2 gap-4 mb-4 bg-white p-4 shadow-lg rounded-lg">
        <input type="text" name="name" placeholder="ชื่อ - นามสกุล" class="p-2 border border-gray-300 rounded">
        <input type="date" name="searchDate" class="p-2 border border-gray-300 rounded">
        <button type="submit" class="col-span-2 p-2 bg-blue-500 text-white rounded">ค้นหา</button>
    </form>
    <!-- ปุ่มดาวน์โหลด PDF -->
    <form method="POST" action="download_pdf.php" class="mb-4">
        <button type="submit" class="p-2 bg-green-500 text-white rounded">ดาวน์โหลด PDF</button>
    </form>
        <div class="overflow-x-auto">
        <table id="carInfoTable" class="table-auto w-full border-collapse border border-gray-300 text-xs sm:text-sm md:text-base">
            <thead class="bg-gray-200">
                <thead>
                <tr class="bg-gray-200 text-left">
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">ชื่อ</th>
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">นามสกุล</th>
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">อีเมล</th>
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">เบอร์โทรติดต่อ</th>
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">รหัสเข้าระบบล็อกอุปกรณ์</th>
                  <th class="border px-2 py-1 whitespace-nowrap"style="font-family: 'Kanit', sans-serif;">วันที่ลงทะเบียน</th>
              </tr>
            </thead>
            <tbody>
              <?php
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<tr>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["firstname"] . "</td>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["lastname"] . "</td>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["email"] . "</td>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["phone"] . "</td>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["password_lock"] . "</td>
                        <td class='border px-2 py-1 whitespace-nowrap'>" . $row["created_at"] . "</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='5' class='p-2 text-center'>ไม่มีข้อมูล</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  <script>
    function searchTable() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll("#carInfoTable tbody tr");

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
