<?php

    $servername = "lockingdevice.pcnone.com";  // เปลี่ยนจาก localhost เป็นโดเมนของคุณ
    $username = "root";  // ชื่อผู้ใช้ที่ใช้เชื่อมต่อกับฐานข้อมูล
    $password = "@Project67 ";  // รหัสผ่านของฐานข้อมูล ถ้ามี
    $dbname = "project";  // ชื่อฐานข้อมูล

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());  // แก้ไขข้อความจาก "Connection success" เป็น "Connection failed"
    }

?>