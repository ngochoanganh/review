<?php
include '../components/connect.php';

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Lấy giá trị hiện tại và giá trị trước khi cập nhật
    $select_user_current = $conn->prepare("SELECT tien, last_update FROM `users` WHERE id = ?");
    $select_user_current->execute([$userId]);

    if ($select_user_current->rowCount() > 0) {
        $user_current = $select_user_current->fetch(PDO::FETCH_ASSOC);

        // Lấy giá trị trước khi cập nhật
        $timeOfUpdate = $user_current['last_update'];
        $select_user_before_update = $conn->prepare("SELECT tien FROM `users` WHERE id = ? AND last_update < ? ORDER BY last_update DESC LIMIT 1");
        $select_user_before_update->execute([$userId, $timeOfUpdate]);

        if ($select_user_before_update->rowCount() > 0) {
            $user_before_update = $select_user_before_update->fetch(PDO::FETCH_ASSOC);

            // Trả về giá trị hiện tại và giá trị trước khi cập nhật dưới dạng JSON
            echo json_encode([
                'current_value' => $user_current['tien'],
                'initial_value' => $user_before_update['tien'],
                'last_update' => $user_current['last_update']
            ]);
        } else {
            // Trả về một giá trị mặc định nếu không tìm thấy giá trị trước khi cập nhật
            echo json_encode([
                'current_value' => $user_current['tien'],
                'initial_value' => 'N/A',
                'last_update' => $user_current['last_update']
            ]);
        }
    } else {
        // Trả về một giá trị mặc định nếu không tìm thấy người dùng
        echo json_encode([
            'current_value' => 'N/A',
            'initial_value' => 'N/A',
            'last_update' => 'N/A'
        ]);
    }
} else {
    // Trả về một giá trị mặc định nếu không có userId được truyền vào
    echo json_encode([
        'current_value' => 'N/A',
        'initial_value' => 'N/A',
        'last_update' => 'N/A'
    ]);
}
?>
