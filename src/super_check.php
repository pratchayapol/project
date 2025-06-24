<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Approval</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Kanit', sans-serif;
        }  
        /* เมนูด้านซ้าย */
        #left-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            width: 100%;
            max-width: 320px;
            overflow-y: auto;
            height: 100vh;
            z-index: 50;
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
    <!-- Navbar -->
    <header class="bg-gradient-to-r from-[#2B547E] to-[#29465B] text-white shadow-lg">
        <div class="container mx-auto px-4 py-6 flex items-center justify-between">
            <button id="menu-toggle" class="text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <h1 class="text-xl font-sans" style="font-size: 16px; font-family: 'Kanit', sans-serif;">คำร้องขอจากผู้ลงทะเบียนในระบบ</h1>
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
            <h2 class="text-lg font-sans"></h2>
            <button id="menu-close" class="text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4"style="font-family: 'Kanit', sans-serif;">
            <ul>
                <li><a href="super_admin.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">หน้าหลัก</a></li>
                <li><a href="super_sum.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ใช้ทั้งหมด</a></li>
                <li><a href="manage.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">จัดการผู้ใช้งาน</a></li>
                <li><a href="chack_device.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ตรวจเช็คอุปกรณ์</a></li>
                <li><a href="user_sum1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">ข้อมูลผู้ชำระค่าปรับ</a></li>
                <li><a href="dashboard1.php" class="block py-2 px-4 text-gray-700 hover:bg-gray-200">สรุปยอดค่าปรับรายเดือน</a></li>
            </ul>
        </nav>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-lg rounded-lg p-4 md:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300 table-auto sm:w-full">
                    <thead>
                        <tr>
                            <th class="px-2 py-2 md:px-4 md:py-2 border border-gray-300"style="font-family: 'Kanit', sans-serif;">ชื่อ</th>
                            <th class="px-2 py-2 md:px-4 md:py-2 border border-gray-300"style="font-family: 'Kanit', sans-serif;">นามสกุล</th>
                            <th class="px-2 py-2 md:px-4 md:py-2 border border-gray-300"style="font-family: 'Kanit', sans-serif;">อีเมล</th>
                            <th class="px-2 py-2 md:px-4 md:py-2 border border-gray-300"style="font-family: 'Kanit', sans-serif;">คำขอ</th>
                        </tr>
                    </thead>
                    <tbody id="user-table">
                        <?php
                        include('server.php');
                        $sql = "SELECT firstname, lastname, email FROM user_register WHERE status = 'pending'";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-2 py-2 md:px-4 md:py-2 border border-gray-300' style='font-family: 'Kanit', sans-serif;'>" . htmlspecialchars($row['firstname']) . "</td>";
                                echo "<td class='px-2 py-2 md:px-4 md:py-2 border border-gray-300' style='font-family: 'Kanit', sans-serif;'>" . htmlspecialchars($row['lastname']) . "</td>";
                                echo "<td class='px-2 py-2 md:px-4 md:py-2 border border-gray-300' style='font-family: 'Kanit', sans-serif;'>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td class='px-2 py-2 md:px-4 md:py-2 border border-gray-300' style='font-family: 'Kanit', sans-serif;'>
                                        <button onclick='showDetails(\"" . htmlspecialchars($row['firstname']) . "\", \"" . htmlspecialchars($row['lastname']) . "\", \"" . htmlspecialchars($row['email']) . "\")' 
                                            class='bg-blue-500 text-white px-2 py-1 md:px-4 md:py-2 rounded' style='font-size: 16px;'>รายละเอียด</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center px-4 py-4'>ไม่พบผู้ใช้ที่รอดำเนินการ.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showDetails(firstname, lastname, email) {
            Swal.fire({
                title: 'รายละเอียดผู้ใช้',
                html: `
                    <div class="text-left">
                        <p><strong>ชื่อ:</strong> ${firstname}</p>
                        <p><strong>นามสกุล:</strong> ${lastname}</p>
                        <p><strong>Email:</strong> ${email}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'อนุมัติ',  
                cancelButtonText: 'ไม่อนุมัติ',
                confirmButtonColor: '#38a169',
                cancelButtonColor: '#e53e3e',
                customClass: {
                    confirmButton: 'hover:bg-green-700 transition duration-300 ease-in-out px-6 py-2 rounded-lg',
                    cancelButton: 'hover:bg-red-700 transition duration-300 ease-in-out px-6 py-2 rounded-lg'
                },
                preConfirm: () => {
                    Swal.fire({
                        title: 'กำลังดำเนินการ...',
                        html: '<div class="spinner-border text-success" role="status"><span class="sr-only">กำลังโหลด...</span></div>',
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    const formData = new FormData();
                    formData.append('email', email);
                    formData.append('action', 'approve');

                    return fetch('super_check_db.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'อนุมัติสำเร็จ!',
                                text: 'ผู้ใช้ได้รับการอนุมัติเรียบร้อยแล้ว',
                                icon: 'success'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire('เกิดข้อผิดพลาด!', data.message, 'error');
                        }
                    })
                    .catch(error => Swal.fire('เกิดข้อผิดพลาด!', error.message, 'error'));
                }
            }).then(result => {
                if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'คุณแน่ใจหรือไม่?',
                        text: 'คุณต้องการลบผู้ใช้นี้ออกจากระบบ?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d'
                    }).then(confirmResult => {
                        if (confirmResult.isConfirmed) {
                            Swal.fire({
                                title: 'กำลังลบ...',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                willOpen: () => Swal.showLoading()
                            });

                            const formData = new FormData();
                            formData.append('delete_email', email);

                            fetch('super_check_db.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        title: 'ลบสำเร็จ!',
                                        text: 'ผู้ใช้ถูกลบออกจากระบบเรียบร้อยแล้ว',
                                        icon: 'success'
                                    }).then(() => location.reload());
                                } else {
                                    Swal.fire('เกิดข้อผิดพลาด!', data.message, 'error');
                                }
                            })
                            .catch(error => Swal.fire('เกิดข้อผิดพลาด!', error.message, 'error'));
                        }
                    });
                }
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
