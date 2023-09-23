<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){

   $sid = $_POST['sid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $type = $_POST['type'];
   $type = filter_var($type, FILTER_SANITIZE_STRING);
   $location = $_POST['location'];
   $loction = filter_var($location, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $update_product = $conn->prepare("UPDATE `shops` SET name = ?, type = ?, location = ?, details = ? WHERE id = ?");
   $update_product->execute([$name, $type, $location, $details, $sid]);

   $message[] = 'shop updated successfully!';


//new
   $old_image = $_POST['old_image'];

   // Check if the old_image is not empty before attempting to delete it
   if (!empty($old_image)) {
       $image_path = '../uploaded_img/' . $old_image;

       // Check if the file exists before attempting to delete it
       if (file_exists($image_path)) {
           unlink($image_path);
           $message[] = 'Old image deleted successfully!';
       }
   }

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $update_image = $conn->prepare("UPDATE `shops` SET image = ? WHERE id = ?");
   $update_image->execute([$image, $sid]);
   move_uploaded_file($image_tmp_name, $image_folder);
   $message[] = 'image updated successfully!';

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">update shop</h1>

   <?php
      $update_id = $_GET['update'];
      $select_shops = $conn->prepare("SELECT * FROM `shops` WHERE id = ?");
      $select_shops->execute([$update_id]);
      if($select_shops->rowCount() > 0){
         while($fetch_shops = $select_shops->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="sid" value="<?= $fetch_shops['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_shops['image']; ?>">
   
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_shops['image']; ?>" alt="">
         </div>
      </div>
      <span>update shop name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter shop name" value="<?= $fetch_shops['name']; ?>">
      <span>update shop type</span>
      <input type="text" name="type" required class="box"  maxlength="100" placeholder="enter shop type" value="<?= $fetch_shops['type']; ?>">
      <span>update shop location</span>
      <input type="text" name="location" required class="box"  maxlength="100" placeholder="enter shop location" value="<?= $fetch_shops['location']; ?>">
      <span>update shop details</span>
      <textarea name="details" class="box" required cols="30" rows="10" placeholder="enter shop details"><?= $fetch_shops['details']; ?></textarea>
      <span>update image(size<5mb)</span>
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
     <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="products.php" class="option-btn">go back</a>
      </div>
   </form>
   
   <?php
         }
      }else{
         echo '<p class="empty">no shops found!</p>';
      }
   ?>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>
<?php

   //header('location:shops.php');
?>