<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <!-- ลิงก์ไปยัง SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        // แสดง SweetAlert
        Swal.fire({
            title: 'สมัครสมาชิกสำเร็จ!',
            text: 'คุณได้ทำการสมัครสมาชิกเรียบร้อยแล้ว',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            // เมื่อกดปุ่ม "ตกลง" จะเปลี่ยนกลับไปยังหน้า index.php
            if (result.isConfirmed) {
                window.location.href = 'index.php';
            }
        });

    </script>
</body>

</html>