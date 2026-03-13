<?php
header('Content-Type: application/json');
require_once './includes/db.php'; 

$cin   = $_GET['cin'] ?? '';
$cout  = $_GET['cout'] ?? '';
$rooms = max(1, (int)($_GET['rooms'] ?? 1));
$type  = $_GET['type'] ?? 'any'; 

if (!$cin || !$cout || strtotime($cout) <= strtotime($cin)) {
    echo json_encode(['success' => false, 'message' => 'Ngày không hợp lệ']);
    exit;
}

try {
    
    if ($type === 'any') {
        $sql = "SELECT p.*, p.gia_mac_dinh FROM phong p WHERE p.trang_thai = 'Trống'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } else {
        $sql = "SELECT p.*, p.gia_mac_dinh FROM phong p WHERE p.trang_thai = 'Trống' AND p.loai_phong = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([ucfirst($type)]);
    }

    $allRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Lọc ra những phòng CÒN TRỐNG trong khoảng ngày yêu cầu
    $availableRooms = [];

    foreach ($allRooms as $room) {
        $roomId = $room['id_phong'];

        // Kiểm tra xem phòng này có bị đặt trùng ngày không
        $checkSql = "
            SELECT COUNT(*) 
            FROM chitietdatphong ctd
            JOIN datphong dp ON ctd.id_datphong = dp.ma_DP
            WHERE ctd.id_phong = :id_phong
              AND dp.trang_thai IN ('cho_xac_nhan', 'da_xac_nhan', 'da_thanh_toan')
              AND ctd.ngay_nhan < :cout 
              AND ctd.ngay_tra > :cin
        ";

        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([
            ':id_phong' => $roomId,
            ':cin'      => $cin,
            ':cout'     => $cout
        ]);

        $isBooked = $checkStmt->fetchColumn();

        if ($isBooked == 0) {
            // Phòng còn trống → thêm vào danh sách
            $availableRooms[] = $room;
        }
    }

    // 3. Gom nhóm theo loại phòng để hiển thị số lượng còn trống
    $resultByType = [];
    $typeMap = ['Standard' => 'Phòng Tiêu Chuẩn', 'Deluxe' => 'Phòng Deluxe', 'Suite' => 'Phòng Suite'];

    foreach ($availableRooms as $room) {
        $loai = $room['loai_phong'];
        if (!isset($resultByType[$loai])) {
            $resultByType[$loai] = [
                'id_phong'     => $room['id_phong'],
                'ten_loai'     => $typeMap[$loai] ?? $loai,
                'gia_dem'      => (float)$room['gia_mac_dinh'],
                'so_luong_con' => 0,
                'hinh_anh'     => $room['hinh_anh'] ?? '/images/default-room.jpg',
                'mo_ta'        => $room['mo_ta'] ?? 'Phòng thoải mái, đầy đủ tiện nghi'
            ];
        }
        $resultByType[$loai]['so_luong_con']++;
    }

    // Tính số đêm
    $nights = floor((strtotime($cout) - strtotime($cin)) / 86400);

    echo json_encode([
        'success' => true,
        'nights'  => $nights,
        'rooms_needed' => $rooms,
        'data'    => array_values($resultByType)
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}
?>