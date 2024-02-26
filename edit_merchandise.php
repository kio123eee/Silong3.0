<?php

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['save'])){

   $merchandise_id = isset($_GET['id']);
   $merchandise_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
   $mod_by = $_POST['mod_by'];
   $mod_by = filter_var($mod_by, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   
   $update_merchandise = $conn->prepare("UPDATE `merchandise` SET mod_by = ?, title = ?, content = ?, status = ? WHERE id = ?");
   $update_merchandise->execute([$mod_by, $title, $content, $status, $merchandise_id]);

   $message[] = 'merchandise updated!';
   
   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../frontendPHP/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `merchandise` WHERE image = ?");
   $select_image->execute([$image]);

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'images size is too large!';
      }elseif($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'please rename your image!';
      }else{
         $update_image = $conn->prepare("UPDATE `merchandise` SET image = ? WHERE id = ?");
         move_uploaded_file($image_tmp_name, $image_folder);
         $update_image->execute([$image, $merchandise_id]);
         if($old_image != $image AND $old_image != ''){
            unlink('../frontendPHP/'.$old_image);
         } 
         $message[] = 'image updated!';
      }
   }
}

if(isset($_POST['delete_merchandise'])){

   $merchandise_id = $_POST['merchandise_id'];
   $merchandise_id = filter_var($merchandise_id, FILTER_SANITIZE_STRING);
   $delete_image = $conn->prepare("SELECT * FROM `merchandise` WHERE id = ?");
   $delete_image->execute([$merchandise_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }
   $delete_merchandise = $conn->prepare("DELETE FROM `merchandise` WHERE id = ?");
   $delete_merchandise->execute([$merchandise_id]);
   $message[] = 'merchandise deleted successfully!';

}

if(isset($_POST['delete_image'])){

   $empty_image = '';
   $merchandise_id = $_POST['merchandise_id'];
   $merchandise_id = filter_var($merchandise_id, FILTER_SANITIZE_STRING);
   $delete_image = $conn->prepare("SELECT * FROM `merchandise` WHERE id = ?");
   $delete_image->execute([$merchandise_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   if($fetch_delete_image['image'] != ''){
      unlink('../uploaded_img/'.$fetch_delete_image['image']);
   }
   $unset_image = $conn->prepare("UPDATE `merchandise` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $merchandise_id]);
   $message[] = 'image deleted successfully!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>merchandise</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>


<?php include 'components/admin_header.php' ?>

<section class="post-editor">

   <br><br><br>
   <h1 class="heading">edit merchandise</h1>
   <br><br><br>

	<?php
      $merchandise_id = $_GET['id'];
      $select_merchandises = $conn->prepare("SELECT * FROM `merchandise` WHERE id = ?");
      $select_merchandises->execute([$merchandise_id]);
      if($select_merchandises->rowCount() > 0){
         while($fetch_merchandises = $select_merchandises->fetch(PDO::FETCH_ASSOC)){
   ?>
	
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mod_by" value="<?= $fetch_profile['name']; ?>">
	
	  <input type="hidden" name="old_image" value="<?= $fetch_merchandises['image']; ?>">	
	
	  <select name="status" class="box" required>
      <option value="<?= $fetch_merchandises['status']; ?>" selected><?= $fetch_merchandises['status']; ?></option>
      <option value="active">active</option>
      <option value="deactive">deactive</option>
      </select>
	
      <p>merchandise title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add merchandise title" class="box" value="<?= $fetch_merchandises['title']; ?>">
      
      <p>description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your description..." cols="30" rows="10"><?= $fetch_merchandises['content']; ?></textarea>

		<p>image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if($fetch_merchandises['image'] != ''){ ?>
         <img src="../uploaded_img/<?= $fetch_merchandises['image']; ?>" class="image" alt="">
         <input type="submit" value="delete image" class="inline-delete-btn" name="delete_image">
      <?php } ?>
      <div class="flex-btn">
         <input type="submit" value="save merchandise" name="save" class="btn">
         <a href="admin_merchandise.php" class="option-btn">go back</a>
         <input type="submit" value="delete merchandise" class="delete-btn" name="delete_merchandise">
      </div>
   </form>
    
	<?php
         }
      }else{
         echo '<p class="empty">no merchandise found!</p>';
   ?>
   <div class="flex-btn">
      <a href="admin_merchandise.php" class="option-btn">view merchandise</a>
      <a href="add_merchandise.php" class="option-btn">add merchandise</a>
   </div>
   <?php
      }
   ?>


</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>