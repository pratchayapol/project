

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
    <title>DataTables Example</title>
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
    </style>
</head>
<body>
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
                header('Content-Type: text/html; charset=utf-8');
                mysqli_set_charset($conn, "utf8");
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["firstname"], 'UTF-8', 'auto')) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["lastname"], 'UTF-8', 'auto')) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["email"], 'UTF-8', 'auto')) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["phone"], 'UTF-8', 'auto')) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["password_lock"], 'UTF-8', 'auto')) . "</td>
                                <td class='border px-2 py-1'>" . htmlspecialchars(mb_convert_encoding($row["created_at"], 'UTF-8', 'auto')) . "</td>
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
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/Thai.json"
                }
            });
        });
    </script>
</body>
</html>
