<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลรถ</title>
    <link rel="stylesheet" href="edit_car.css">
</head>
<body>

<div class="table-container">
    <h1>แก้ไขข้อมูลรถ</h1>
    <table>
        <thead>
            <tr>
                <th>เลขทะเบียน</th>
                <th>จังหวัด</th>
                <th>ยี่ห้อ</th>
                <th>ประเภทรถ</th>
                <th>ยอดชำระค่าปรับ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'server.php';
            $sql = "SELECT * FROM ข้อมูลรถยนต์";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['plate_number']}</td>
                        <td>{$row['province']}</td>
                        <td>{$row['brand']}</td>
                        <td>{$row['car_type']}</td>
                        <td>{$row['price']}</td>
                        <td><button onclick='openEditPopup(".json_encode($row).")'>แก้ไข</button></td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>ไม่พบข้อมูล</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="back-button">
        <button type="button" onclick="confirmBack()">ย้อนกลับ</button>
    </div>
</div>

<!-- Popup -->
<div class="overlay" id="overlay"></div>
<div class="popup" id="editModal">
    <h2>แก้ไขข้อมูลรถ</h2>
    <form action="EditInformation_db.php" method="post">
        <input type="hidden" name="car_id" id="car_id">
        <div class="form-group">
            <label for="plate_number">เลขทะเบียนรถ:</label>
            <input type="text" id="plate_number" name="plate_number" required>
        </div>
        <div class="form-group">
            <label for="province">จังหวัด:</label>
            <select id="province" name="province" required>
                <option value="">--เลือกจังหวัด--</option>
                <?php
                $sql = "SELECT name_th FROM ข้อมูลจังหวัด";
                $provinces = $conn->query($sql);
                if ($provinces->num_rows > 0) {
                    while ($province = $provinces->fetch_assoc()) {
                        echo '<option value="'.$province['name_th'].'">'.$province['name_th'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="brand">ยี่ห้อ:</label>
            <select id="brand" name="brand" required>
                <option value="">--เลือกยี่ห้อ--</option>
                <?php
                $sql = "SELECT name FROM ข้อมูลยี่ห้อรถยนต์";
                $brands = $conn->query($sql);
                if ($brands->num_rows > 0) {
                    while ($brand = $brands->fetch_assoc()) {
                        echo '<option value="'.$brand['name'].'">'.$brand['name'].'</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="car_type">ประเภทรถ:</label>
            <input type="text" id="car_type" name="car_type" required>
        </div>
        <div class="form-group">
            <label for="price">ยอดชำระค่าปรับ:</label>
            <input type="number" id="price" name="price" required>
        </div>
        <div class="buttons">
            <button type="button" onclick="confirmClose()">ยกเลิก</button>
            <button type="submit">บันทึก</button>
        </div>
    </form>
</div>

<script>
    function openEditPopup(data) {
        document.getElementById('car_id').value = data.id;
        document.getElementById('plate_number').value = data.plate_number;

        // จังหวัด
        const provinceDropdown = document.getElementById('province');
        for (let i = 0; i < provinceDropdown.options.length; i++) {
            if (provinceDropdown.options[i].value === data.province) {
                provinceDropdown.selectedIndex = i;
                break;
            }
        }

        // ยี่ห้อ
        const brandDropdown = document.getElementById('brand');
        for (let i = 0; i < brandDropdown.options.length; i++) {
            if (brandDropdown.options[i].value === data.brand) {
                brandDropdown.selectedIndex = i;
                break;
            }
        }

        document.getElementById('car_type').value = data.car_type;
        document.getElementById('price').value = data.price;

        document.getElementById('overlay').style.display = 'block';
        document.getElementById('editModal').style.display = 'block';
    }

    function confirmClose() {
        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการแก้ไขข้อมูล?")) {
            closePopup();
        }
    }

    function closePopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('editModal').style.display = 'none';
    }

    function confirmBack() {
        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการย้อนกลับ? ข้อมูลที่ยังไม่ได้บันทึกจะสูญหาย")) {
            window.location.href = "menu.php"; // ใส่ URL หรือหน้าที่คุณต้องการไป
        }
    }
</script>

</body>
</html>
