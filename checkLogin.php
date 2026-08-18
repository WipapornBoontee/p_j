<?php
include 'condb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // SQL ตรวจสอบผู้ใช้
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result); // เก็บข้อมูลผู้ใช้ในตัวแปร $user

            // ตรวจสอบค่าว่า id หรือ user_id ถูกต้องตามฐานข้อมูล
            if (isset($user['id'])) { // ถ้าคอลัมน์ในฐานข้อมูลคือ 'id'
                $_SESSION['username'] = $user['username']; // ตั้งค่าชื่อผู้ใช้ใน session
                $_SESSION['user_id'] = $user['id']; // เก็บ id ใน session (เปลี่ยน 'user_id' เป็น 'id')

            } else {
                // แสดงข้อผิดพลาดหากไม่มี 'id'
                echo "Error: id not found in database.";
                exit;
            }

            // ตรวจสอบบทบาทผู้ใช้และแสดง SweetAlert
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Redirecting...</title>
                <!-- ลิงก์ SweetAlert2 -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>";

            if ($user['role'] === 'admin') {
                echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        text: 'ยินดีต้อนรับ Admin',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'admin_dashboard.php';
                        }
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'เข้าสู่ระบบสำเร็จ!',
                        text: 'ยินดีต้อนรับ User',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'user_dashboard.php';
                        }
                    });
                </script>";
            }

            echo "</body></html>";
        } else {
            // แจ้งเตือนข้อผิดพลาด
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Login Failed</title>
                <!-- ลิงก์ SweetAlert2 -->
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
            <script>
                Swal.fire({
                    title: 'เข้าสู่ระบบล้มเหลว!',
                    text: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
                    icon: 'error',
                    confirmButtonText: 'ลองอีกครั้ง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            </script>
            </body></html>";
        }
    } else {
        // แจ้งเตือนว่ากรอกข้อมูลไม่ครบ
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Incomplete Data</title>
            <!-- ลิงก์ SweetAlert2 -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
        <script>
            Swal.fire({
                title: 'ข้อมูลไม่ครบ!',
                text: 'กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน',
                icon: 'warning',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        </script>
        </body></html>";
    }
}
