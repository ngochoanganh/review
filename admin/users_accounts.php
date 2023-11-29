<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}


include '../components/connect.php';

// Kết nối đến cơ sở dữ liệu
$conn = new PDO($db_name, $user_name, $user_password);

//userId = $_POST['abcd'];
// Lấy thông tin của thực thể cần chuyển đổi`

$select = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select->execute([$userId]);


// $sql = "SELECT * FROM `users` WHERE id = ?";
// $result = $conn->query($sql);
// Xóa thực thể khỏi bảng user
$delete =  $conn->prepare("DELETE FROM `users` WHERE id = ?");
$delete->execute([$userId]);



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users accounts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- users accounts section starts  -->

<section class="accounts">

   <h1 class="heading">users account</h1>

   <div class="box-container">

   <?php
      $select_account = $conn->prepare("SELECT * FROM `users`");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){ 
            $user_id = $fetch_accounts['id']; 
            $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
            $count_user_comments->execute([$user_id]);
            $total_user_comments = $count_user_comments->rowCount();
            $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
            $count_user_likes->execute([$user_id]);
            $total_user_likes = $count_user_likes->rowCount();
   ?>
   <div class="box">
      <p> users id : <span><?= $user_id; ?></span> </p>
      <p> username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> total comments : <span><?= $total_user_comments; ?></span> </p>
      <p> total likes : <span><?= $total_user_likes; ?></span> </p>
      <div class="flex-btn">
      
      </div>
      <form action="add_quyen.php" method="post">
         <input type="hidden" name="abc" value="<?= $fetch_accounts['id']; ?>" on>
         <button type="submit" value="Chuyển đổi" class="btn btn-primary">Chuyển đổi</button>
      </form>
      <form action="" method="post">
         <input type="hidden" value="<?= $fetch_accounts['id']; ?>" on>
         <button type="submit" value="" class="btn btn-primary">Xoá</button>
      </form>

   </div>
   <?php
      }
   }else{
      echo '<p class="empty">no accounts available</p>';
   }
   ?>

   </div>

</section>

<!-- users accounts section ends -->




















<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>