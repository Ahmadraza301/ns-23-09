<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location: admin_login.php');
}

if (isset($_POST['update'])) {
    $pid = $_POST['pid'];
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'], FILTER_SANITIZE_STRING);

    // Update product details in the "products" table
    $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, details = ? WHERE id = ?");
    $update_product->execute([$name, $price, $details, $pid]);

    $message[] = 'Product updated successfully!';

    // Handle image updates
    $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_size_01 = $_FILES['image_01']['size'];
   $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
   $image_folder_01 = '../uploaded_img/'.$image_01;

   if(!empty($image_01)){
      if($image_size_01 > 5000000){
         $message[] = 'Please insert images having size less than 5mb!';
      }else{
         $update_image_01 = $conn->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
         $update_image_01->execute([$image_01, $pid]);
         move_uploaded_file($image_tmp_name_01, $image_folder_01);
         unlink('../uploaded_img/'.$old_image_01);
         $message[] = 'image 01 updated successfully!';
      }
   }

   $old_image_02 = $_POST['old_image_02'];
   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_size_02 = $_FILES['image_02']['size'];
   $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
   $image_folder_02 = '../uploaded_img/'.$image_02;

   if(!empty($image_02)){
      if($image_size_02 > 5000000){
         $message[] = 'Please insert images having size less than 5mb!';
      }else{
         $update_image_02 = $conn->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
         $update_image_02->execute([$image_02, $pid]);
         move_uploaded_file($image_tmp_name_02, $image_folder_02);
         unlink('../uploaded_img/'.$old_image_02);
         $message[] = 'image 02 updated successfully!';
      }
   }

   $old_image_03 = $_POST['old_image_03'];
   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_size_03 = $_FILES['image_03']['size'];
   $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
   $image_folder_03 = '../uploaded_img/'.$image_03;

   if(!empty($image_03)){
      if($image_size_03 > 5000000){
         $message[] = 'Please insert images having size less than 5mb!';
      }else{
         $update_image_03 = $conn->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
         $update_image_03->execute([$image_03, $pid]);
         move_uploaded_file($image_tmp_name_03, $image_folder_03);
         unlink('../uploaded_img/'.$old_image_03);
         $message[] = 'image 03 updated successfully!';
      }
   }


    // Update shop details
    $shopNames = $_POST['shop_name'];
    $stocks = $_POST['stocks'];

    // Delete existing shop details for the product
    $deleteShopDetails = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $deleteShopDetails->execute([$pid]);

    // Insert the updated shop details
    for ($i = 0; $i < count($shopNames); $i++) {
        $shopName = $shopNames[$i];
        $stock = $stocks[$i];

        $insertShopDetails = $conn->prepare("INSERT INTO `products` (id, shop_name, stock) VALUES (?, ?, ?)");
        $insertShopDetails->execute([$pid, $shopName, $stock]);

        $message[] = 'Shop details updated successfully!';
    }
}

// Fetch product details
$update_id = $_GET['update'];
$select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
$select_products->execute([$update_id]);

if ($select_products->rowCount() > 0) {
    $fetch_products = $select_products->fetch(PDO::FETCH_ASSOC);
} else {
    echo '<p class="empty">No product found!</p>';
}

// Fetch initial shop details
$initialShopDetails = array();
$selectInitialShopDetails = $conn->prepare("SELECT shop_name, stock FROM `products` WHERE id = ?");
$selectInitialShopDetails->execute([$update_id]);

while ($shopDetail = $selectInitialShopDetails->fetch(PDO::FETCH_ASSOC)) {
    $initialShopDetails[] = $shopDetail;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <!-- Add your CSS and other header content here -->
    <link rel="stylesheet" href="../css/admin_style.css">
   <link rel="stylesheet" type="text/css" href="../css/admin_shop.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php if (isset($fetch_products)): ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">
      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
         </div>
      </div>
      <span>update product name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['name']; ?>">
      <span>update product price</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
      <span>update category</span>
      <input type="text" name="category" required class="box"  maxlength="100" placeholder="enter category"  value="<?= $fetch_products['category']; ?>">
      <span>update details</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <span>update image 01(size<5mb)</span>
      <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>update image 02(size<5mb)</span>
      <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      <span>update image 03(size<5mb)</span>
      <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">

      <!-- Shop details section -->
      <h2>update shop details</h2>
      <div class="shop-details-container">
         <table class="shop-details">
            <thead>
               <tr>
                  <th>Shop Name</th>
                  <th>Stocks</th>
                  <th>Action</th>
               </tr>
            </thead>
            <!-- ... Rest of your HTML and PHP code ... -->

<tbody>
    <?php
    // Display initial shop details
    foreach ($initialShopDetails as $index => $shopDetail):
    ?>
    <tr>
        <td><input type="text" name="shop_name[<?= $index; ?>]" class="box" required maxlength="100" placeholder="Enter shop name" value="<?= $shopDetail['shop_name']; ?>"></td>
        <td><input type="number" name="stocks[<?= $index; ?>]" min="0" class="box" required placeholder="Enter stocks" value="<?= $shopDetail['stock']; ?>"></td>
        <td><button type="button" class="remove-row">Remove</button></td>
    </tr>
    <?php endforeach; ?>
    <!-- Add a new row for shop details -->
    <tr id="shop-detail-template" style="display: none;">
        <td><input type="text" name="shop_name[]" class="box" required maxlength="100" placeholder="Enter shop name"></td>
        <td><input type="number" name="stocks[]" min="0" class="box" required placeholder="Enter stocks"></td>
        <td><button type="button" class="remove-row">Remove</button></td>
    </tr>
</tbody>

         </table>
         <button type="button" class="add-row">Add Row</button>
      </div>

      <!-- Update and go back buttons -->
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="Update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php endif; ?>

</section>

<!-- Include your JavaScript and other footer content here -->
<script src="../js/admin_script.js"></script>
<script src="../js/admin_shop.js"></script>

</body>
</html>
