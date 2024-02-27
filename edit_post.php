<?php

error_reporting(E_ALL & ~E_DEPRECATED);

include 'components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['save'])) {
   $post_id = isset($_GET['id']);
   $post_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
   $mod_by = $_POST['mod_by'];
   $mod_by = filter_var($mod_by, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $content = $_POST['content'];
   $content = filter_var($content, FILTER_SANITIZE_STRING);
   $status = $_POST['status'];
   $status = filter_var($status, FILTER_SANITIZE_STRING);
   
   // Update post details
   $update_post = $conn->prepare("UPDATE `posts` SET mod_by = ?, title = ?, content = ?, status = ? WHERE id = ?");
   $update_post->execute([$mod_by, $title, $content, $status, $post_id]);

   $message[] = 'Post updated!';
   
   $old_image = $_POST['old_image'];
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '/storage/uploads/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ?");
   $select_image->execute([$image]);

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'Images size is too large!';
      } elseif ($select_image->rowCount() > 0 && $image != '') {
         $message[] = 'Please rename your image!';
      } else {
         // Update image path in the database
         $update_image = $conn->prepare("UPDATE `posts` SET image = ? WHERE id = ?");
         move_uploaded_file($image_tmp_name, $image_folder);
         $update_image->execute([$image, $post_id]);
         // Remove old image if exists
         if ($old_image != $image && $old_image != '') {
            unlink('/storage/uploads/'.$old_image);
         } 
         $message[] = 'Image updated!';
      }
   }
}

if (isset($_POST['delete_post'])) {
   // Handle post deletion
   $post_id = $_POST['post_id'];
   $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
   // Fetch post details including the image path
   $delete_image = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   // Delete image file if exists
   if ($fetch_delete_image['image'] != '') {
      unlink('/storage/uploads/'.$fetch_delete_image['image']);
   }
   // Delete post from the database
   $delete_post = $conn->prepare("DELETE FROM `posts` WHERE id = ?");
   $delete_post->execute([$post_id]);
   $message[] = 'Post deleted successfully!';
}

if (isset($_POST['delete_image'])) {
   // Handle image deletion
   $empty_image = '';
   $post_id = $_POST['post_id'];
   $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
   // Fetch post details including the image path
   $delete_image = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
   $delete_image->execute([$post_id]);
   $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
   // Delete image file if exists
   if ($fetch_delete_image['image'] != '') {
      unlink('/storage/uploads/'.$fetch_delete_image['image']);
   }
   // Update image path in the database to empty
   $unset_image = $conn->prepare("UPDATE `posts` SET image = ? WHERE id = ?");
   $unset_image->execute([$empty_image, $post_id]);
   $message[] = 'Image deleted successfully!';
}

error_reporting(E_ALL & ~E_DEPRECATED);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Blog Posts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'components/admin_header.php' ?>

<section class="post-editor">
   <br><br><br>
   <h1 class="heading">Edit Post</h1>
   <br><br><br>

   <?php
      $post_id = $_GET['id'];
      $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE id = ?");
      $select_posts->execute([$post_id]);
      if ($select_posts->rowCount() > 0) {
         while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="mod_by" value="<?= $fetch_profile['name']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_posts['image']; ?>">
      <select name="status" class="box" required>
         <option value="<?= $fetch_posts['status']; ?>" selected><?= $fetch_posts['status']; ?></option>
         <option value="active">Active</option>
         <option value="deactive">Deactive</option>
      </select>
      <p>Post Title <span>*</span></p>
      <input type="text" name="title" maxlength="100" required placeholder="Add post title" class="box" value="<?= $fetch_posts['title']; ?>">
      <p>Description <span>*</span></p>
      <textarea name="content" class="box" required maxlength="10000" placeholder="Write your description..." cols="30" rows="10"><?= $fetch_posts['content']; ?></textarea>
      <p>Image</p>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      <?php if ($fetch_posts['image'] != '') { ?>
         <img src="/storage/uploads/<?= $fetch_posts['image']; ?>" class="image" alt="">
         <input type="submit" value="Delete Image" class="inline-delete-btn" name="delete_image">
      <?php } ?>
      <div class="flex-btn">
         <input type="submit" value="Save Post" name="save" class="btn">
         <a href="admin_posts.php" class="option-btn">Go Back</a>
         <input type="submit" value="Delete Post" class="delete-btn" name="delete_post">
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">No posts found!</p>';
      }
   ?>
</section>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>
