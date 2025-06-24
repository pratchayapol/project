<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลที่ลงทะเบียน</title>
    <!-- เชื่อมต่อ Tailwind CSS จาก CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.1.8/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;700&display=swap" rel="stylesheet">
</head>
    <style>
            body {
                font-family: 'Kanit', sans-serif;
                background: url('https://cdn.pixabay.com/photo/2019/04/10/11/56/watercolor-4116932_640.png') no-repeat center center fixed;
                background-size: cover;
            }
    </style>
</head>
<body class="bg-gray-100 antialiased"style="font-family: 'Kanit', sans-serif;">
    <div id="app"></div> <!-- เนื้อหาจะถูกแสดงที่นี่จาก JavaScript -->
    
    <script>
        window.onload = function() {
            // ใช้ Fetch API ดึงข้อมูลจาก PHP
            fetch('data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('app').innerHTML = `<p class="text-xl text-red-600 text-center">${data.error}</p>`;
                    } else {
                        // สร้าง HTML ที่จะแสดงผล
                        let html = `
                            <div class="max-w-7xl mx-auto p-8 bg-white shadow-xl rounded-lg mt-12">
                                <h2 class="text-4xl text-gray-800 mb-8 text-center" style="font-family: 'Kanit', sans-serif;">ข้อมูลที่ลงทะเบียน</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                    <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                        <h3 class="text-2xl text-gray-700 mb-4" style="font-family: 'Kanit', sans-serif;">ข้อมูลผู้ใช้</h3>
                                        <p class="text-lg text-gray-600 mb-2">ชื่อ: <span style="font-family: 'Kanit', sans-serif;"">${data.first_name} ${data.last_name}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">อีเมลล์: <span style="font-family: 'Kanit', sans-serif;"">${data.email}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">เบอร์โทร: <span style="font-family: 'Kanit', sans-serif;"">${data.phone_number}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">จังหวัด: <span style="font-family: 'Kanit', sans-serif;"">${data.province}</span></p>
                                    </div>
                                    <div class="bg-gray-50 p-6 rounded-lg shadow-md">
                                        <h3 class="text-2xl text-gray-700 mb-4" style="font-family: 'Kanit', sans-serif;">ข้อมูลรถ</h3>
                                        <p class="text-lg text-gray-600 mb-2">ทะเบียนรถ: <span style="font-family: 'Kanit', sans-serif;">${data.plate_number}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">ประเภทรถ: <span style="font-family: 'Kanit', sans-serif;">${data.car_type}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">ยี่ห้อ: <span style="font-family: 'Kanit', sans-serif;">${data.brand}</span></p>
                                        <p class="text-lg text-gray-600 mb-2">ยอดชำระค่าปรับ: <span style="font-family: 'Kanit', sans-serif;">${data.price} บาท</span></p>
                                    </div>
                                </div>
                                <div class="flex space-x-6 justify-center mt-8">
                                    <form method="post" action="loginuser.php" onsubmit="return confirmCancel();">
                                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-600">ยกเลิก</button>
                                    </form>
                                    <form method="post" action="edit.php">
                                        <button type="submit" class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-orange-600">แก้ไข</button>
                                    </form>
                                    <form method="post" action="transfer.php">
                                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-600">ต่อไป</button>
                                    </form>
                                </div>
                            </div>
                        `;
                        document.getElementById('app').innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('app').innerHTML = `<p class="text-xl text-red-600 text-center">เกิดข้อผิดพลาดในการโหลดข้อมูล</p>`;
                });
        }

        function confirmCancel() {
            return confirm('คุณแน่ใจว่าจะออกจากหน้านี้?');
        }
    </script>
</body>
</html>
