<?php
include 'condb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบว่า Confirm Password ตรงกับ Password
    if ($password !== $confirm_password) {
        echo "รหัสผ่านไม่ตรงกัน!";
        exit();
    }

    // SQL สำหรับ INSERT
    $sql = "INSERT INTO users (first_name, last_name, email, username, password) 
    VALUES ('$first_name', '$last_name', '$email', '$username', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "สมัครสมาชิกสำเร็จ!";
        header("Location: success.php"); // เปลี่ยนเส้นทางไปยังหน้าสำเร็จ
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
