<?php

// Kết nối với database
include 'components/connect.php';

// Lấy dữ liệu từ form
$name = $_POST['name'];
$tien = $_POST['tien'];

// Kiểm tra giá trị của trường age
if (!is_numeric($age) || $age < 0 || $age > 100) {
  // Tạo thông báo lỗi
  $message[] = 'age must be a number between 0 and 100!';
} else {
  // Cập nhật trường age trong database
  $update_user = $conn->prepare("UPDATE `users` SET age = ? WHERE name = ?");
  $update_user->execute([$age, $name]);

  // Tạo thông báo thành công
  $message[] = 'age updated successfully!';
}

// Xóa thông báo lỗi trước đó
$message = array_diff($message, ['age must be a number between 0 and 100!']);

// Hiển thị thông báo
if (count($message) > 0) {
  // Hiển thị thông báo lỗi
  echo '<div class="alert alert-danger">' . implode('<br>', $message) . '</div>';
} else {
  // Hiển thị thông báo thành công
  echo '<div class="alert alert-success">Age updated successfully!</div>';
}

?>

?>