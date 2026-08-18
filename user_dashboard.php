<?php
session_start();  // เริ่มต้น session
// ตรวจสอบหากผู้ใช้ล็อกอินสำเร็จ
if (!isset($_SESSION['username']) and $_SESSION['user_id']) {
    // หากไม่ได้ล็อกอินให้รีไดเร็กไปหน้า login
    header('Location: index.php');
    exit;
}
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบแจ้งเตือนการซ่อมอุปกรณ์</title>
    <!-- ลิงก์ไปยัง Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-md">
            <a class="navbar-brand" href="#" style="color: #f8f9fa;">
                <i class="fas fa-bell"></i> ระบบแจ้งเตือนการซ่อมอุปกรณ์
            </a>
            <!-- เพิ่มปุ่มสำหรับจัดการขนาดหน้าจอในมือถือ -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- เนื้อหาหลักของ Navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- ms-auto ใช้สำหรับเลื่อนเมนูไปขวา -->
                    <li class="nav-item">
                        <a class="nav-link active" href="#" style="color: #f8f9fa;">
                            <i class="fas fa-home"></i> หน้าหลัก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm" href="javascript:void(0);" style="color:rgb(11, 67, 123);" onclick="ConfirmLogOut()">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                        <script>
                            function ConfirmLogOut() {
                                Swal.fire({
                                    title: 'คุณแน่ใจว่าต้องการออกจากระบบ?',
                                    text: "หากคุณออกจากระบบจะต้องทำการล็อกอินใหม่",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'ออกจากระบบ',
                                    cancelButtonText: 'ยกเลิก',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // ถ้าผู้ใช้กดยืนยัน, ไปที่หน้า LogOut.php
                                        window.location.href = 'LogOut.php';
                                    }
                                });
                            }
                        </script>

                    </li>
                </ul>
            </div>
        </div>
    </nav> <!-- Navbar -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แจ้งปัญหาใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ฟอร์มแจ้งปัญหา -->
                    <form action="insertDevice.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="device_name" class="form-label">ชื่ออุปกรณ์:</label>
                            <input type="text" id="device_name" name="device_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">รายละเอียดปัญหา:</label>
                            <textarea id="description" name="description" rows="6" class="form-control" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="fileToUpload" class="form-label">อัพโหลดรูปภาพ:</label>
                            <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">ส่งข้อมูล</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนเนื้อหา -->
    <main class="container mt-4">
        <div class="d-flex justify-content-center">
            <p>สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?> อยากให้เราซ่อมอะไรเหรอ?</p>
        </div>

        <!-- ส่วนตารางแสดงสถานะ -->
        <div class="card p-4">

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <!-- ปุ่มสำหรับเปิด Modal -->
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-exclamation-circle"></i> แจ้งปัญหาใหม่
                </button>
                <button class="btn btn-outline-primary" onclick="loadDevices()">
                    <i class="fas fa-sync-alt"></i> โหลดข้อมูลใหม่
                </button>
            </div>
            <h2 class="card-title">สถานะอุปกรณ์ของคุณ</h2>
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>ชื่ออุปกรณ์</th>
                        <th>สถานะ</th>
                        <th>วันที่แจ้ง</th>
                        <th>รายละเอียด</th>
                    </tr>
                </thead>
                <tbody id="device-status-table">
                    <!-- ข้อมูลจะแสดงที่นี่ -->
                </tbody>
            </table>
        </div>
    </main>
    <script>
        // ฟังก์ชันโหลดข้อมูลจากฐานข้อมูล
        function loadDevices() {
            fetch('api.php?action=get_devices')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById('device-status-table');
                    tableBody.innerHTML = '';

                    if (data.length > 0) {
                        // วนลูปแสดงข้อมูล
                        data.forEach((device, index) => {
                            const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${device.device_name}</td>
                            <td>${device.status}</td>
                            <td>${device.created_at}</td>
                            <td>
                            <button class="btn btn-secondary btn-sm" onclick="viewDetails(${device.id})">
                                ดูรายละเอียด
                            </button>
                        </td>
                        </tr>
                    `;
                            tableBody.innerHTML += row; // เพิ่มแถวใหม่ในตาราง
                        });
                    } else {
                        // ถ้าไม่มีข้อมูล
                        const row = `
                    <tr>
                        <td colspan="4" class="text-center">อุ๊ปส์!? ไม่มีอุปกรณ์ที่ส่งซ่อมนะ!</td>
                    </tr>
                `;
                        tableBody.innerHTML = row;
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    const tableBody = document.getElementById('device-status-table');
                    tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center">เกิดข้อผิดพลาดในการดึงข้อมูล</td>
                </tr>
            `;
                });
        }

        // โหลดข้อมูลเมื่อหน้าเว็บโหลดเสร็จ
        document.addEventListener('DOMContentLoaded', loadDevices);
    </script>

    <!-- Modal -->
    <div class="modal fade" id="Detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title">รายละเอียดอุปกรณ์</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>ชื่ออุปกรณ์:</strong> <span id="device-name" style="color: #037592;"></span></p>
                    <p><strong>รายละเอียดปัญหา:</strong> <span id="device-description" style="color: #037592;"></span></p>
                    <p><strong>สถานะ:</strong> <span id="device-status" style="color: #037592;"></span></p>
                    <p><strong>วันที่สร้าง:</strong> <span id="device-created-at" style="color: #037592;"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewDetails(deviceId) {
            // Fetch API สำหรับดึงข้อมูลจากเซิร์ฟเวอร์
            fetch(`api.php?action=get_device_details&id=${deviceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        // กรณีข้อมูลไม่ถูกต้อง
                        alert(data.error);
                    } else {
                        // แสดงข้อมูลใน Modal
                        document.getElementById('device-name').textContent = data.device_name;
                        document.getElementById('device-description').textContent = data.description;
                        document.getElementById('device-status').textContent = data.status;
                        document.getElementById('device-created-at').textContent = data.created_at;

                        // เปิด Modal
                        const myModal = new bootstrap.Modal(document.getElementById('Detail'));
                        myModal.show();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการดึงข้อมูล!');
                });
        }
    </script>



    <!-- ลิงก์ไปยัง Bootstrap JS และ Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- อย่าลืมแนบไฟล์ของ Bootstrap JS และ CSS ตามนี้ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>