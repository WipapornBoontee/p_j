<?php
// เริ่ม session
session_start();

// ล้างข้อมูล session
session_unset(); // ล้างตัวแปรใน session ทั้งหมด
session_destroy(); // ทำลาย session

// เปลี่ยนเส้นทางกลับไปยังหน้าเข้าสู่ระบบ
header("Location: index.php");
exit();
