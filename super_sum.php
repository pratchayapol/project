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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataTables Example</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.dataTables.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>

    <!-- Plugins for exporting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
</head>
<body>

    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011-04-25</td>
                <td>$320,800</td>
            </tr>
            <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011-07-25</td>
                <td>$170,750</td>
            </tr>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

</body>
</html>
