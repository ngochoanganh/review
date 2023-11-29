<?php
include '../components/connect.php';

// Lấy danh sách tất cả người dùng
$select_users = $conn->prepare("SELECT id, name, tien, last_update FROM `users`");
$select_users->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Statistics</title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Users Statistics</h1>
        
    <?php
if (isset($_SESSION['success_message'])) {
    echo '<div style="color: green;">' . $_SESSION['success_message'] . '</div>';
    // Xóa thông báo sau khi hiển thị
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
    // Xóa thông báo sau khi hiển thị
    unset($_SESSION['error_message']);
}
?>



    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Initial Tien</th>
                <th>Current Tien</th>
                <th>Last Update</th>
            </tr>
        </thead>
        <tbody id="userList">
            <?php foreach ($select_users->fetchAll(PDO::FETCH_ASSOC) as $user) { ?>
                <tr id="user<?= $user['id']; ?>">
                    <td><?= $user['name']; ?></td>
                    <td><?= $user['tien']; ?></td>
                    <td id="tien<?= $user['id']; ?>"><?= $user['tien']; ?></td>
                    <td><?= $user['last_update']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<!-- Trong phần HTML/PHP chính -->
<tbody id="userList">
    <?php foreach ($select_users->fetchAll(PDO::FETCH_ASSOC) as $user) { ?>
        <tr id="user<?= $user['id']; ?>">
            <td><?= $user['name']; ?></td>
            <td id="initialTien<?= $user['id']; ?>">Loading...</td>
            <td id="currentTien<?= $user['id']; ?>"><?= $user['tien']; ?></td>
            <td><?= $user['last_update']; ?></td>
        </tr>
    <?php } ?>
</tbody>

<script>
    // Hàm thực hiện gửi yêu cầu AJAX để lấy giá trị mới và giá trị trước khi cập nhật
    function updateTienValue(userId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = JSON.parse(this.responseText);

                // Kiểm tra xem khóa "current_value" và "initial_value" có tồn tại không
                if ('current_value' in response && 'initial_value' in response) {
                    var currentValue = response.current_value;
                    var initialValue = response.initial_value;
                    var lastUpdate = response.last_update;

                    var initialTienElement = document.getElementById("initialTien" + userId);

                    // Kiểm tra giá trị trước khi cập nhật và hiển thị
                    if (initialTienElement.innerText === 'Loading...') {
                        initialTienElement.innerText = initialValue;
                    }

                    var currentTienElement = document.getElementById("currentTien" + userId);

                    // Kiểm tra giá trị mới và cập nhật nếu cần
                    if (currentTienElement.innerText !== currentValue) {
                        var initialTien = initialTienElement.innerText;
                        currentTienElement.innerText = currentValue;

                        // Hiển thị thông báo biến động giá trị
                        alert("Giá trị tiền của người dùng " + userId + " đã biến động từ " + initialTien + " đến " + currentValue + " vào lúc " + lastUpdate);
                    }
                }
            }
        };
        xhttp.open("GET", "get_updated_tien.php?userId=" + userId, true);
        xhttp.send();
    }

    // Hàm tự động cập nhật giá trị theo khoảng thời gian
    setInterval(function() {
        <?php foreach ($select_users->fetchAll(PDO::FETCH_ASSOC) as $user) { ?>
            updateTienValue(<?= $user['id']; ?>);
        <?php } ?>
    }, 5000); // Cập nhật mỗi 5 giây
</script>


</body>
</html>
