<?php
session_start();
include '../config/config.php';

if(isset($_SESSION['admin_id']) && isset($_SESSION['admin_name'])){
   $admin_id = $_SESSION['admin_id'];
   $admin_name = $_SESSION['admin_name'];
}else{
   header('Location: ../index.php');
}

if(isset($_POST['publish'])){
   $status = 'active';
   $message = [];

   $title = $_POST['title'];
   $content = $_POST['content'];

   $select_image = $conn->prepare("SELECT * FROM `merchandise` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if(isset($_FILES['image']['name'])){
      $image = $_FILES['image']['name'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = '/storage/uploads/'.$image;

      if($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'please rename your image!';
      }elseif($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   }else{
      $image = '';
   }

   if(empty($message)){
      $insert_merchandise = $conn->prepare("INSERT INTO `merchandise`(admin_id, admin_name, title, content, image, status) VALUES(?,?,?,?,?,?)");
      $insert_merchandise->execute([$admin_id, $admin_name, $title, $content, $image, $status]);
      $message[] = 'merchandise published!';
   }
}

if(isset($_POST['draft'])){
   $status = 'deactive';
   $message = [];

   $title = $_POST['title'];
   $content = $_POST['content'];

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '/storage/uploads/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `merchandise` WHERE image = ? AND admin_id = ?");
   $select_image->execute([$image, $admin_id]);

   if(isset($image)){
      if($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'image name repeated!';
      }elseif($image_size > 2000000){
         $message[] = 'images size is too large!';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   }else{
      $image = '';
   }

   if($select_image->rowCount() > 0 AND $image != ''){
      $message[] = 'please rename your image!';
   }else{
      $insert_merchandise = $conn->prepare("INSERT INTO `merchandise`(admin_id, admin_name, title, content, image, status) VALUES(?,?,?,?,?,?)");
      $insert_merchandise->execute([$admin_id, $admin_name, $title, $content, $image, $status]);
      $message[] = 'draft saved!';
   }
}

error_reporting(E_ALL & ~E_DEPRECATED);
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
   <h1 class="heading">add new merchandise</h1>
   <br><br><br>

   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="admin_name" value="<?= $fetch_profile['name']; ?>">
      
      <p>merchandise image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
     
      <p>merchandise title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="add merchandise title" class="box">
      
      <p>description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="write your description..." cols="30" rows="10"></textarea>

      <br> <br><br>
      <div class="flex-btn">
         <input type="submit" value="publish merchandise" name="publish" class="btn">
		 <input type="submit" value="save draft" name="draft" class="option-btn">
      </div>
   </form>

</section>


<!-- custom js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>
