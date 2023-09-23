<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_shop'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $location = filter_var($location, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

    
            


                    $insert_shops = $conn->prepare("INSERT INTO `shops` (name, details, type, image, location) VALUES (?, ?, ?, ?, ?)");

                    // Insert the product information for each shop
                     $insert_shops->execute([$name, $details, $type, $image, $location]);
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'new shop added!';
}



if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_shop_image = $conn->prepare("SELECT * FROM `shops` WHERE id = ?");
   $delete_shop_image->execute([$delete_id]);
   $fetch_delete_image = $delete_shop_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_shops = $conn->prepare("DELETE FROM `shops` WHERE id = ?");
   $delete_shops->execute([$delete_id]);
   header('location:shops.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shops</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" type="text/css" href="../css/admin_shop.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="add-products">

   <h1 class="heading">add shops</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>shop name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter shop name" name="name">
         </div>
         <div class="inputBox">
            <span>type (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter shop type"  name="type">
         </div>
        <div class="inputBox">
            <span>location (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter shop address" name="location">
         </div>
        <div class="inputBox">
            <span>image (size<5mb)</span>
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>shop details (required)</span>
            <textarea name="details" placeholder="enter shop details" class="box" required maxlength="500" cols="30" rows="10" ></textarea >
         </div>

</div>









      </div>
      
      <input type="submit" value="add shop" class="btn" name="add_shop">
   </form>

</section>

<section class="show-products">

   <h1 class="heading">shops added</h1>

   <div class="box-container">

   <?php
      $select_shops = $conn->prepare("SELECT * FROM `shops`");
      $select_shops->execute();
      if($select_shops->rowCount() > 0){
         while($insert_shops = $select_shops->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $insert_shops['image']; ?>" alt="">
      <div class="name"><?= $insert_shops['name']; ?></div>
      <!-- <div class="type" style="font-size:2em;"><span><?= $insert_shops['type']; ?></span>/-</div> -->
      <div class="type"><span><?= $insert_shops['type']; ?></span></div>
      <div class="details"><span><?= $insert_shops['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_shop.php?update=<?= $insert_shops['id']; ?>" class="option-btn">update</a>
         <a href="shops.php?delete=<?= $insert_shops['id']; ?>" class="delete-btn" onclick="return confirm('delete this shop?');">delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no shops added yet!</p>';
      }
   ?>
   
   </div>

</section>








<script src="../js/admin_script.js"></script>
<script src="../js/admin_shop.js"></script>
   
</body>
</html>


