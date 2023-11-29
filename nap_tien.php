<?php

include 'components/connect.php';

session_start();



if(isset($_POST['submit'])){

  $tien = $_POST['tien'];

  if ($tien > 100) {
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_user->execute([$user_id]);
        if ($select_user->rowCount() > 0) {
            $users = $select_user->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            $username = $user["name"];
            $password = $user["password"];
            $tiencu = $user["tien"];
        }
         $sql = "INSERT INTO admin (name, password) VALUES ('$username', '$password')";
         $conn->query($sql);
         $row = $select_user->fetch(PDO::FETCH_ASSOC);
         print_r($row);
        $tien_moi = $tiencu + $tien;
        $update_user = $conn->prepare("UPDATE `users` SET tien = ? WHERE id = ?");
         $update_user->execute([$tien_moi, $user_id]);
    
            echo "Thực hiện chuyển đổi thành công!";
        } else {
            echo "Không tìm thấy người dùng có ID là $userId";
        }
        
        }
     else{
        $user_id = '';
     };
    

  //header("Location: admin/dashboard.php");
  } else {
    echo 'Số tiền phải lớn hơn 100!';
  }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>
<section class="form-container">

   <form action="" method="post">
      <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

      <input type="int" name="tien" required placeholder="nhập số tiền" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">

      <input type="submit" value="register now" name="submit" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
