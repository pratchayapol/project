

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบอุปกรณ์สำหรับเจ้าหน้าที่ตำรวจ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <h1 class="text-xl" style="font-family: 'Kanit', sans-serif;">ตรวจเช็คสถานะอุปกรณ์</h1>
            <div class="flex items-center space-x-6">
            <a href="logout.php" class="block px-4 py-2 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">Logout</a>
                <div class="relative group cursor-pointer">
                    <!-- User Icon with External SVG -->
                    <img src="https://www.svgrepo.com/show/524199/user-circle.svg" alt="User Icon" class="h-6 w-6 group-hover:text-gray-200" style="filter: brightness(0) invert(1);">
                    <!-- Dropdown -->
                    <div class="absolute right-0 mt-2 w-40 bg-white text-gray-800 shadow-lg rounded-md opacity-0 group-hover:opacity-100 group-hover:translate-y-2 transition-all duration-200">
                        <a href="profile.php" class="block px-4 py-2 hover:bg-gray-100" style="font-family: 'Kanit', sans-serif;">Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Left Sliding Menu -->
    <div id="left-menu" class="fixed top-0 left-0 w-64 h-full bg-white shadow-lg z-50">
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
                <li><a href="edit_car.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">แก้ไขข้อมูลรถ</a></li>
                <li><a href="dashboard.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
                <li><a href="user_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
            </ul>
        </nav>
    </div>

    <div class="container mx-auto px-4 py-6" style="font-family: 'Kanit', sans-serif;">
        <form method="POST" class="grid grid-cols-2 gap-4 mb-4 bg-white p-4 shadow-lg rounded-lg">
            <input type="text" name="searchName" placeholder="ชื่อ - นามสกุล" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchName']) ? $_POST['searchName'] : ''; ?>">
            <input type="date" name="searchDate" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchDate']) ? $_POST['searchDate'] : ''; ?>">
            <input type="email" name="searchEmail" placeholder="อีเมล" class="p-2 border border-gray-300 rounded" value="<?php echo isset($_POST['searchEmail']) ? $_POST['searchEmail'] : ''; ?>">
            <button type="submit" class="col-span-2 p-2 bg-blue-500 text-white rounded">ค้นหา</button>
        </form>
        <div class="overflow-x-auto">
        <table id="example" class="table-auto w-full border-collapse border border-gray-300 text-xs sm:text-sm md:text-base">
            <thead class="bg-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-left" style="font-family: 'Kanit', sans-serif;">
                        <th class="border px-2 py-1 whitespace-nowrap">ทะเบียน</th>
                        <th class="border px-2 py-1 whitespace-nowrap">จังหวัด</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ประเภทรถ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ยี่ห้อรถ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">ยอดชำระค่าปรับ</th>
                        <th class="border px-2 py-1 whitespace-nowrap">รหัสปลดล็อก</th>                        
                        <th class="border px-2 py-1 whitespace-nowrap">อุปกรณ์เครื่องที่</th>
                        <th class="border px-2 py-1 whitespace-nowrap">เวลาที่เพิ่ม</th>
                    </tr>
                </thead>
                <tbody id="example">
                    <!-- ข้อมูลจะถูกเพิ่มที่นี่ผ่าน JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // ดึงข้อมูลจากฐานข้อมูลและแสดงในตาราง
        fetch('device_db.php')
            .then(response => response.json())
            .then(data => {
                const deviceList = document.querySelector('#example');
                data.forEach(device => {
                    const row = document.createElement('tr');
                    row.classList.add('border-b', 'border-gray-200');
                    row.innerHTML = `
                        <td class='border px-2 py-1 whitespace-nowrap' style='font-family: 'Kanit', sans-serif;'> ${device.plate_number}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.province}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.car_type}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.brand}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.price}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.unlockcar}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.device}</td>
                        <td class='border px-2 py-1 whitespace-nowrap' style="font-family: 'Kanit', sans-serif;"> ${device.created_at}</td>
                    `;
                    deviceList.appendChild(row);
                });
            })
            .catch(error => console.error('Error fetching device data:', error));
            document.getElementById('menu-toggle').addEventListener('click', function () {
            document.getElementById('left-menu').classList.toggle('open');
        });

        document.getElementById('menu-close').addEventListener('click', function () {
            document.getElementById('left-menu').classList.remove('open');
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
                searching: false,  // ปิดการค้นหา
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
                }
            });
        });
    </script>
</body>
</html>
