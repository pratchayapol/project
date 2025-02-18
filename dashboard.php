<?php
// เชื่อมต่อกับฐานข้อมูล
include('server.php');
session_start();

// ตัวอย่างการตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['email'])) {
    // หากไม่ได้เข้าสู่ระบบ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
    header("Location: login.php");
    exit();
}

// ตรวจสอบบทบาทของผู้ใช้
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // หากบทบาทไม่ใช่ admin ให้เปลี่ยนเส้นทางไปยังหน้าไม่มีสิทธิ์
    header("Location: webboard.php");
    exit();
}

// ฟังก์ชันเพื่อดึงข้อมูลจากตาราง "หลักฐานการโอน"
function getMonthlyData($month, $year) {
    global $conn;
    
    // คำสั่ง SQL ที่จะใช้ดึงข้อมูลจากตาราง "หลักฐานการโอน"
    $sql = "SELECT SUM(amount) AS total_amount
            FROM transfer
            WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?";
    
    // ตรวจสอบการเตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($sql)) {
        // ทำการจับคู่ตัวแปรกับคำสั่ง SQL
        $stmt->bind_param("ii", $month, $year);
        
        // เรียกใช้คำสั่ง SQL
        if ($stmt->execute()) {
            // ดึงผลลัพธ์จากการ query
            $result = $stmt->get_result();
            
            // คืนค่าผลลัพธ์
            return $result->fetch_assoc();
        } else {
            // แสดงข้อความหากไม่สามารถ execute คำสั่งได้
            echo "ข้อผิดพลาดในการ execute คำสั่ง SQL: " . $stmt->error;
            return false;
        }
    } else {
        // แสดงข้อความหากการเตรียมคำสั่ง SQL ล้มเหลว
        echo "ข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        return false;
    }
}

// ฟังก์ชันเพื่อดึงข้อมูลรายสัปดาห์
function getWeeklyData($month, $year) {
    global $conn;
    
    // คำสั่ง SQL ที่จะใช้ดึงข้อมูลรายสัปดาห์
    $sql = "SELECT WEEK(created_at) AS week, SUM(amount) AS total_amount
            FROM transfer
            WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?
            GROUP BY week
            ORDER BY week ASC";
    
    // ตรวจสอบการเตรียมคำสั่ง SQL
    if ($stmt = $conn->prepare($sql)) {
        // ทำการจับคู่ตัวแปรกับคำสั่ง SQL
        $stmt->bind_param("ii", $month, $year);
        
        // เรียกใช้คำสั่ง SQL
        if ($stmt->execute()) {
            // ดึงผลลัพธ์จากการ query
            $result = $stmt->get_result();
            
            // คืนค่าผลลัพธ์
            return $result;
        } else {
            // แสดงข้อความหากไม่สามารถ execute คำสั่งได้
            echo "ข้อผิดพลาดในการ execute คำสั่ง SQL: " . $stmt->error;
            return false;
        }
    } else {
        // แสดงข้อความหากการเตรียมคำสั่ง SQL ล้มเหลว
        echo "ข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error;
        return false;
    }
}

// ตรวจสอบว่าได้เลือกเดือนและปีหรือยัง
$month = isset($_GET['month']) ? $_GET['month'] : date('m');  // ใช้เดือนปัจจุบันถ้าไม่ได้เลือก
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');  // ใช้ปีปัจจุบันถ้าไม่ได้เลือก

// ดึงข้อมูลจากฐานข้อมูล
$data = getMonthlyData($month, $year);

// ตรวจสอบว่าได้ข้อมูลหรือไม่
if ($data && isset($data['total_amount'])) {
    // ถ้าได้ข้อมูล ก็แสดงผล
    $totalAmount = $data['total_amount'];
} else {
    // ถ้าไม่ได้ข้อมูล ให้กำหนดค่าเป็น 0 หรือข้อความอื่น
    $totalAmount = 0;
}

// ดึงข้อมูลรายสัปดาห์
$weeklyData = getWeeklyData($month, $year);
$weeks = [];
$weeklyAmounts = [];
while ($row = $weeklyData->fetch_assoc()) {
    $weeks[] = "สัปดาห์ " . $row['week'];
    $weeklyAmounts[] = $row['total_amount'];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สรุปผลการดำเนินงานรายเดือน</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
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
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <!-- Menu Toggle Button -->
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-xl font-semibold" style="font-size: 16px;">สรุปยอดชำระค่าปรับรายเดือน</h1>
            <div class="flex items-center space-x-6">
                <div class="relative group cursor-pointer">
                    <!-- User Icon with External SVG -->
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <!-- Dropdown -->
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="Profile1.php" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50">
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
            <li><a href="menu_home.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="AddInformation.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">เพิ่มข้อมูล</a></li>
                <li><a href="edit_car.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">แก้ไขข้อมูลรถยนต์</a></li>
                <li><a href="device.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจเช็คสถานะอุปกรณ์</a></li>
                <li><a href="user_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-center text-blue-700 mb-4">สรุปยอดค่าชำระรายเดือน</h1>

            <!-- ฟอร์มเลือกเดือนและปี -->
            <form method="get" action="" class="mb-8">
                <div class="flex justify-center space-x-4">
                    <select name="month" class="px-4 py-2 border rounded-lg">
                        <option value="1" <?php echo (isset($_GET['month']) && $_GET['month'] == 1) ? 'selected' : ''; ?>>มกราคม</option>
                        <option value="2" <?php echo (isset($_GET['month']) && $_GET['month'] == 2) ? 'selected' : ''; ?>>กุมภาพันธ์</option>
                        <option value="3" <?php echo (isset($_GET['month']) && $_GET['month'] == 3) ? 'selected' : ''; ?>>มีนาคม</option>
                        <option value="4" <?php echo (isset($_GET['month']) && $_GET['month'] == 4) ? 'selected' : ''; ?>>เมษายน</option>
                        <option value="5" <?php echo (isset($_GET['month']) && $_GET['month'] == 5) ? 'selected' : ''; ?>>พฤษภาคม</option>
                        <option value="6" <?php echo (isset($_GET['month']) && $_GET['month'] == 6) ? 'selected' : ''; ?>>มิถุนายน</option>
                        <option value="7" <?php echo (isset($_GET['month']) && $_GET['month'] == 7) ? 'selected' : ''; ?>>กรกฎาคม</option>
                        <option value="8" <?php echo (isset($_GET['month']) && $_GET['month'] == 8) ? 'selected' : ''; ?>>สิงหาคม</option>
                        <option value="9" <?php echo (isset($_GET['month']) && $_GET['month'] == 9) ? 'selected' : ''; ?>>กันยายน</option>
                        <option value="10" <?php echo (isset($_GET['month']) && $_GET['month'] == 10) ? 'selected' : ''; ?>>ตุลาคม</option>
                        <option value="11" <?php echo (isset($_GET['month']) && $_GET['month'] == 11) ? 'selected' : ''; ?>>พฤศจิกายน</option>
                        <option value="12" <?php echo (isset($_GET['month']) && $_GET['month'] == 12) ? 'selected' : ''; ?>>ธันวาคม</option>
                    </select>
                    <select name="year" class="px-4 py-2 border rounded-lg">
                        <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear; $i >= $currentYear - 5; $i--) {
                            echo "<option value='$i' " . (isset($_GET['year']) && $_GET['year'] == $i ? 'selected' : '') . ">$i</option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg">เลือก</button>
                </div>
            </form>

            <p class="text-center text-gray-600 mb-8">
                เดือน: <?php echo isset($_GET['month']) ? $_GET['month'] : $month; ?> / <?php echo isset($_GET['year']) ? $_GET['year'] : $year; ?>
            </p>

            <!-- สรุปข้อมูลหลัก -->
            <div class="flex flex-col sm:flex-row justify-center items-center sm:space-x-4 mb-8">
                <div class="bg-blue-100 text-blue-800 rounded-lg shadow-md p-6 w-full sm:w-1/3 text-center">
                    <h3 class="text-lg sm:text-xl font-semibold">จำนวนเงินรวม</h3>
                    <p class="text-xl sm:text-2xl font-bold mt-2"><?php echo number_format($totalAmount, 2); ?> บาท</p>
                </div>
            </div>

            <!-- รายงานรายละเอียด -->
            <div class="mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-green-700 mb-4">ข้อมูลแยกตามประเภทการโอน</h3>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-300 text-sm sm:text-base">
                        <thead>
                            <tr class="bg-blue-700 text-white">
                                <th class="px-2 sm:px-4 py-2 border border-gray-300">ประเภทสถานะ</th>
                                <th class="px-2 sm:px-4 py-2 border border-gray-300">ยอดรวม (บาท)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // ดึงข้อมูลประเภทสถานะและยอดรวมจากฐานข้อมูล
                            $month = isset($_GET['month']) ? $_GET['month'] : date('m');
                            $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

                            $sql = "SELECT status, SUM(amount) AS total_amount 
                                    FROM transfer 
                                    WHERE MONTH(created_at) = ? AND YEAR(created_at) = ? 
                                    GROUP BY status";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ii", $month, $year);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // แสดงผลข้อมูล
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-100'>";
                                echo "<td class='px-2 sm:px-4 py-2 border border-gray-300'>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td class='px-2 sm:px-4 py-2 border border-gray-300'>" . number_format($row['total_amount'], 2) . " บาท</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- กราฟรายเดือน -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg mt-6">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">กราฟรายเดือน</h3>
                <div id="monthlyChart" class="w-full h-64"></div>
            </div>

            <!-- กราฟรายสัปดาห์ -->
            <div class="bg-white p-4 sm:p-6 rounded-lg shadow-lg mt-6">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">กราฟรายสัปดาห์</h3>
                <div id="weeklyChart" class="w-full h-64"></div>
            </div>
        </div>
    </div>

    <!-- Script สำหรับ ApexCharts -->
    <script>
        // กราฟรายเดือน
        var monthlyOptions = {
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
            },
            series: [{
                name: 'ยอดเงิน',
                data: [<?php echo $totalAmount; ?>]
            }],
            xaxis: {
                categories: ['ยอดรวมรายเดือน'],
            },
            colors: ['#4CAF50'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%',
                },
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                theme: 'light'
            }
        };

        var monthlyChart = new ApexCharts(document.querySelector("#monthlyChart"), monthlyOptions);
        monthlyChart.render();

        // กราฟรายสัปดาห์
        var weeklyOptions = {
            chart: {
                type: 'bar',
                height: '100%',
                toolbar: { show: false },
            },
            series: [{
                name: 'ยอดเงินรายสัปดาห์',
                data: <?php echo json_encode($weeklyAmounts); ?>
            }],
            xaxis: {
                categories: <?php echo json_encode($weeks); ?>,
            },
            colors: ['#FF9800'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '50%',
                },
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                theme: 'light'
            }
        };

        var weeklyChart = new ApexCharts(document.querySelector("#weeklyChart"), weeklyOptions);
        weeklyChart.render();

        document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('left-menu').classList.toggle('open');
        });

        document.getElementById('menu-close').addEventListener('click', function () {
            document.getElementById('left-menu').classList.remove('open');
        });
    </script>
</body>
</html>

