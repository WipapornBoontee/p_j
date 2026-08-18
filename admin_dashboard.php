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

        <!-- ส่วนเนื้อหา -->
        <main class="container mt-4">

            <div class="d-flex justify-content-center">
                <p>สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?> ซ่อมอะไรเรียบร้อยแล้วอัพเดทด้วยนะ!</p>
            </div>

            <!-- ปุ่มแจ้งเตือน รอการตรวจสอบ -->
            <div class="alert d-flex justify-content-end align-items-center" role="alert">
                <button id="pending-button" class="btn btn-warning position-relative" data-bs-toggle="modal" data-bs-target="#pendingModal">
                    อุปกรณ์รออยู่นะ!
                    <span id="pending-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </button>
            </div>

            <!-- Modal สำหรับสถานะ "รอการตรวจสอบ" -->
            <div class="modal fade" id="pendingModal" tabindex="-1" aria-labelledby="pendingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pendingModalLabel">สถานะ: รอการตรวจสอบ</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ชื่ออุปกรณ์</th>
                                        <th>รายละเอียด</th>
                                        <th>อัพเดทเข้ามาเมื่อ</th>
                                        <th>ดำเนินการ</th>
                                    </tr>
                                </thead>
                                <tbody id="pending-table">
                                    <!-- ข้อมูลจะถูกเติมตรงนี้ -->
                                    <script>
                                        function populatePendingTable(devices) {
                                            const tableBody = document.getElementById("pending-table");
                                            tableBody.innerHTML = ""; // ล้างข้อมูลในตารางก่อน

                                            if (devices.length > 0) {
                                                devices.forEach((device, index) => {
                                                    const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${device.device_name}</td>
                <td>${device.description}</td>
                <td>${device.created_at}</td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="approveDevice(${device.id}, 'กำลังซ่อม')">
                        <i class="fas fa-check-circle"></i> อนุมัติ
                    </a>
                </td>
            </tr>
            `;
                                                    tableBody.innerHTML += row;
                                                });
                                            } else {
                                                tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center">ไม่มีข้อมูลในสถานะนี้</td>
            </tr>
        `;
                                            }
                                        }
                                    </script>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ตาราง กำลังซ่อม -->
            <h3 class="mt-4">สถานะ: กำลังซ่อม</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่ออุปกรณ์</th>
                        <th>รายละเอียด</th>
                        <th>วันที่สร้าง</th>
                        <th>ดำเนินการ</th>
                    </tr>
                </thead>
                <tbody id="in-progress-table">
                    <!-- ข้อมูลจะถูกเติมตรงนี้ -->
                    <script>
                        function populateInProgressTable(devices) {
                            const tableBody = document.getElementById("in-progress-table");
                            tableBody.innerHTML = ""; // ล้างข้อมูลในตารางก่อน

                            if (devices.length > 0) {
                                devices.forEach((device, index) => {
                                    const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${device.device_name}</td>
                <td>
                    <a href="javascript:void(0)" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" onclick="viewDetails(${device.id})">
                        <i class="fas fa-eye"></i> ดูรายละเอียด
                    </a>
                </td>
                <td>${device.created_at}</td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onclick="approveDevice(${device.id}, 'ซ่อมเสร็จแล้ว')">
                        <i class="fas fa-check-circle"></i> ส่งงาน
                    </a>
                </td>
            </tr>
            `;
                                    tableBody.innerHTML += row;
                                });
                            } else {
                                tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center">ไม่มีข้อมูลในสถานะนี้</td>
            </tr>
        `;
                            }
                        }
                    </script>
                </tbody>
            </table>

            <!-- ตาราง ซ่อมเสร็จแล้ว -->
            <h3 class="mt-4">สถานะ: ซ่อมเสร็จแล้ว</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่ออุปกรณ์</th>
                        <th>รายละเอียด</th>
                        <th>วันที่สร้าง</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody id="completed-table">
                    <!-- ข้อมูลจะถูกเติมตรงนี้ -->
                    <script>
                        function populateCompletedTable(devices) {
                            const tableBody = document.getElementById("completed-table");
                            tableBody.innerHTML = ""; // ล้างข้อมูลในตารางก่อน

                            if (devices.length > 0) {
                                devices.forEach((device, index) => {
                                    const row = `
            <tr>
                <td>${index + 1}</td>
                <td>${device.device_name}</td>
                 <td>
                    <a href="javascript:void(0)" class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" onclick="viewDetails(${device.id})">
                        <i class="fas fa-eye"></i> ดูรายละเอียด
                    </a>
                </td>
                <td>${device.created_at}</td>
                <td>
                    <span class="badge bg-success">ซ่อมเสร็จแล้ว</span>
                </td>
            </tr>
            `;
                                    tableBody.innerHTML += row;
                                });
                            } else {
                                tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center">ไม่มีข้อมูลในสถานะนี้</td>
            </tr>
        `;
                            }
                        }
                    </script>
                </tbody>
            </table>

        </main>

        <script>
            // ฟังก์ชันโหลดข้อมูลจากฐานข้อมูล
            function loadDevices() {
                const apiUrl = "apiA.php?action=get_all_devices";

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        const pending = data.filter(device => device.status === "รอการตรวจสอบ");
                        const inProgress = data.filter(device => device.status === "กำลังซ่อม");
                        const completed = data.filter(device => device.status === "ซ่อมเสร็จแล้ว");

                        // เรียกใช้ฟังก์ชันเติมข้อมูลในแต่ละตาราง
                        populatePendingTable(pending);
                        populateInProgressTable(inProgress);
                        populateCompletedTable(completed);

                        updatePendingCount(pending.length);
                    })
                    .catch(error => console.error("Error fetching devices:", error));
            }

            // ฟังก์ชันอนุมัติอุปกรณ์
            function approveDevice(deviceId, nextStatus) {
                const statusText = nextStatus === "กำลังซ่อม" ? "กำลังซ่อม" : "ซ่อมเสร็จแล้ว";

                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: `การอนุมัติจะเปลี่ยนสถานะเป็น '${statusText}'`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่, อนุมัติเลย!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // API URL สำหรับอัปเดตสถานะ
                        const apiUrl = `apiA.php?action=update_status&id=${deviceId}&status=${encodeURIComponent(nextStatus)}`;

                        fetch(apiUrl, {
                                method: "POST"
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('สำเร็จ!', `สถานะถูกเปลี่ยนเป็น "${statusText}".`, 'success');
                                    loadDevices(); // โหลดข้อมูลใหม่
                                } else {
                                    Swal.fire('ล้มเหลว!', 'เกิดข้อผิดพลาดในการอนุมัติ.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error("Error approving device:", error);
                                Swal.fire('ล้มเหลว!', 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์.', 'error');
                            });
                    }
                });
            }

            // ฟังก์ชันอัปเดตจำนวนแจ้งเตือนในปุ่ม
            function updatePendingCount(count) {
                const pendingCount = document.getElementById("pending-count");
                pendingCount.textContent = count;
                if (count > 0) {
                    pendingCount.style.display = "inline";
                } else {
                    pendingCount.style.display = "none";
                }
            }

            // ฟังก์ชันดูรายละเอียด
            function viewDetails(deviceId) {
                // API URL สำหรับรายละเอียด
                const apiUrl = `apiA.php?action=get_device_details&id=${deviceId}`;

                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            Swal.fire('เกิดข้อผิดพลาด!', data.error, 'error');
                        } else {
                            // เติมข้อมูลใน Modal
                            document.getElementById("modal-device-name").textContent = data.device_name;
                            document.getElementById("modal-description").textContent = data.description;
                            document.getElementById("modal-created-at").textContent = data.created_at;

                            const imageElement = document.getElementById("modal-device-image");
                            if (data.image_path) {
                                imageElement.src = data.image_path; // ตั้งค่า src ของรูปภาพ
                                imageElement.style.display = "block";
                            } else {
                                imageElement.src = ""; // ไม่มีรูปภาพ
                                imageElement.style.display = "none";
                            }

                            // เปิด Modal
                            const detailsModal = new bootstrap.Modal(document.getElementById("detailsModal"));
                            detailsModal.show();
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching device details:", error);
                        Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถโหลดข้อมูลได้.', 'error');
                    });
            }

            // โหลดข้อมูลเมื่อหน้าเว็บโหลดเสร็จ
            document.addEventListener("DOMContentLoaded", loadDevices);
        </script>

        <!-- Modal -->
        <div class="modal fade" id="Detail" tabindex="-1" aria-labelledby="DetailLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="DetailLabel">รายละเอียดอุปกรณ์</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>ชื่ออุปกรณ์:</strong> <span id="modal-device-name"></span></p>
                        <p><strong>รายละเอียดปัญหา:</strong> <span id="modal-description"></span></p>
                        <p><strong>สถานะ:</strong> <span id="modal-status"></span></p>
                        <p><strong>วันที่สร้าง:</strong> <span id="modal-created-at"></span></p>
                        <div>
                            <strong>รูปภาพ:</strong><br>
                            <img class="mt-2" id="modal-image" src="" alt="รูปภาพอุปกรณ์" style="max-width: 100%; height: auto; display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

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
                fetch(`apiA.php?action=get_device_details&id=${deviceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                        } else {
                            document.getElementById('modal-device-name').textContent = data.device_name;
                            document.getElementById('modal-description').textContent = data.description;
                            document.getElementById('modal-status').textContent = data.status;
                            document.getElementById('modal-created-at').textContent = data.created_at;

                            const imageElement = document.getElementById('modal-image');
                            if (data.image_path) {
                                imageElement.src = data.image_path;
                                imageElement.style.display = 'block';
                            } else {
                                imageElement.style.display = 'none';
                            }

                            const myModal = new bootstrap.Modal(document.getElementById('Detail'));
                            myModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching device details:', error);
                        alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
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