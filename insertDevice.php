<?php
session_start();
include 'condb.php';

if ($conn === false) {
    die("ERROR: Could not connect to the database. " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('กรุณาเข้าสู่ระบบก่อนทำรายการ!');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_name = mysqli_real_escape_string($conn, $_POST['device_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = 'รอการตรวจสอบ';
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $image_path = null;

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === 0) {
        $target_dir = "uploads/";
        $image_name = uniqid() . "_" . basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // ตรวจสอบว่าเป็นไฟล์ภาพ
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
            if (in_array($imageFileType, $allowed_types)) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $image_path = $target_file;
                } else {
                    echo "<script>
                        alert('ไม่สามารถอัปโหลดไฟล์ได้ กรุณาติดต่อผู้ดูแลระบบ!');
                        window.history.back();
                    </script>";
                    exit;
                }
            } else {
                echo "<script>
                    alert('ไฟล์ไม่รองรับ อนุญาตเฉพาะ JPG, PNG, JPEG, GIF!');
                    window.history.back();
                </script>";
                exit;
            }
        } else {
            echo "<script>
                alert('ไฟล์ไม่ใช่รูปภาพ!');
                window.history.back();
            </script>";
            exit;
        }
    }

    $sql = "INSERT INTO `devices` (`user_id`, `device_name`, `description`, `status`, `image_path`, `created_at`, `updated_at`) 
            VALUES ('$user_id', '$device_name', '$description', '$status', '$image_path', '$created_at', '$updated_at')";

    if (mysqli_query($conn, $sql)) {
        echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>แจ้งปัญหาสำเร็จ</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: 'แจ้งปัญหาสำเร็จ!',
                        text: 'ระบบกำลังนำคุณกลับไปหน้าแรก.',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'user_dashboard.php';
                        }
                    });
                </script>
            </body>
            </html>
        ";
    } else {
        echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>เกิดข้อผิดพลาด</title>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        title: 'เกิดข้อผิดพลาด!',
                        text: 'ไม่สามารถบันทึกข้อมูลได้ กรุณาติดต่อผู้ดูแลระบบ.',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>
        ";
    }
}
