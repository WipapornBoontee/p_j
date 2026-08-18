<?php
// เชื่อมต่อฐานข้อมูล
include 'condb.php';

// ดึงข้อมูลอุปกรณ์
if ($_GET['action'] == 'get_devices') {
    $sql = "SELECT id, device_name, status, created_at FROM devices ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $devices = [];
    while ($row = $result->fetch_assoc()) {
        $devices[] = $row;
    }

    // ส่งข้อมูลกลับในรูปแบบ JSON
    header('Content-Type: application/json');
    echo json_encode($devices);
    exit();
}

if ($_GET['action'] === 'get_device_details' && isset($_GET['id'])) {
    $device_id = intval($_GET['id']); // แปลง ID ให้เป็นเลข
    $sql = "SELECT device_name, description, status, created_at FROM devices WHERE id = $device_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $device = mysqli_fetch_assoc($result);
        header('Content-Type: application/json');
        echo json_encode($device); // ส่งข้อมูลกลับในรูปแบบ JSON
    } else {
        echo json_encode(['error' => 'ไม่พบข้อมูลอุปกรณ์']);
    }
    exit;
}

?>
