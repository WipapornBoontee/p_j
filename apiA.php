<?php
include 'condb.php';

// API ดึงข้อมูลทั้งหมด
if ($_GET['action'] === 'get_all_devices') {
    $sql = "SELECT * FROM devices ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    $devices = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $devices[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($devices);
    exit;
}

// API ดึงรายละเอียดตาม ID
if ($_GET['action'] === 'get_device_details' && isset($_GET['id'])) {
    $device_id = intval($_GET['id']);
    $sql = "SELECT * FROM devices WHERE id = $device_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $device = mysqli_fetch_assoc($result);
        header('Content-Type: application/json');
        echo json_encode($device);
    } else {
        echo json_encode(['error' => 'ไม่พบข้อมูลอุปกรณ์']);
    }
    exit;
}

// อนุมัติ สถานะ
if ($_GET['action'] === 'approve_device' && isset($_GET['id'])) {
    $device_id = intval($_GET['id']);
    $sql = "UPDATE devices SET status = 'กำลังซ่อม' WHERE id = $device_id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
    exit;
}


// อัพเดทสถานะ
if ($_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $device_id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $sql = "UPDATE devices SET status = '$status' WHERE id = $device_id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
    exit;
}
